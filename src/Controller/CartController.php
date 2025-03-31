<?php

namespace App\Controller;

use App\Services\CartCalculatorServiceInterface;
use App\Entity\CartItem;
use App\Entity\OrderDetails;
use App\Event\Events\OrderPlacedEvent;
use App\Form\OrderDetailsFormType;
use App\Services\AppContextInterface;
use App\Services\CartItemServiceInterface;
use App\Services\CartServiceInterface;
use App\Services\OrderServiceInterface;
use App\Services\ProductServiceInterface;
use App\Services\UserServiceInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Cart Controller - Manages all cart-related functionalities including:
 * - Viewing cart contents
 * - Adding/removing items
 * - Adjusting quantities
 * - Checkout process
 */
class CartController extends AbstractController
{
    public function __construct(
        private CartServiceInterface $cartService,
        private CartCalculatorServiceInterface $cartCalculatorService,
        private CartItemServiceInterface $cartItemService,
        private UserServiceInterface $userService,
        private ProductServiceInterface $productService,
        private OrderServiceInterface $orderService,
        private EventDispatcherInterface $eventDispatcher,
        private AppContextInterface $appContext
    ) {

    }

    /**
     * Displays the current user's cart
     *
     * @return Response Rendered cart page
     */
    #[Route('/cart', name: 'app_cart')]
    #[IsGranted('ROLE_CUSTOMER')]
    public function cartPage(): Response
    {
        // Get cart of currently authenticated user
        $cart = $this->appContext->getCurrentCart();

        return $this->render('cart/index.html.twig', ['cart' => $cart]);
    }

    /**
     * Adds an item to the cart
     *
     * @param int $productId ID of the product to add
     * @return Response Redirects to cart page
     * @throws Exception If item cannot be added
     */
    #[Route('/cart/add/{productId<\d+>}', name: 'add_item_to_cart')]
    #[IsGranted('ROLE_CUSTOMER')] // Added security annotation
    public function addItem(int $productId): Response
    {
        try {
            $product = $this->productService->getOneById($productId);
            $cart = $this->appContext->getCurrentCart();
            $cartItem = new CartItem($product, $cart);

            if ($this->cartService->checkItemExists($cart, $cartItem)) {
                $this->addFlash('error', 'Item already exists in the cart');
                return $this->redirectToRoute('app_cart');
            }

            $this->cartService->addCartItem($cart, $cartItem);


        } catch (Exception $e) {
            $this->addFlash('error', 'Could not add item to cart');
            throw new Exception('Item not added to cart: ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_cart');
    }

    /**
     * Removes an item from the cart
     *
     * @param int $cartItemId ID of the cart item to remove
     * @return Response Redirects to cart page
     * @throws Exception If item cannot be removed
     */
    #[Route('/cart/remove/{cartItemId<\d+>}', name: 'remove_item_from_cart')]
    #[IsGranted('ROLE_CUSTOMER')]
    public function removeItem(int $cartItemId): Response
    {
        try {
            $cart = $this->appContext->getCurrentCart();
            $cartItem = $this->cartItemService->getCartItem($cartItemId);

            $this->cartService->removeCartItem($cart, $cartItem);


        } catch (Exception $e) {
            $this->addFlash('error', 'Could not remove item');
            throw new Exception('Item not removed: ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_cart');
    }

    /**
     * Increases quantity of a cart item
     *
     * @param int $cartItemId ID of the cart item
     * @return Response Redirects to cart page
     * @throws Exception If quantity cannot be updated
     */
    #[Route('/cart/plusQuantity/{cartItemId<\d+>}', name: 'add_quantity_item')]
    #[IsGranted('ROLE_CUSTOMER')]
    public function addItemQuantity(int $cartItemId): Response
    {
        try {
            $cart = $this->appContext->getCurrentCart();
            $this->cartItemService->plusQuantity($cartItemId);
            $this->cartCalculatorService->updateCartTotals($cart);


        } catch (Exception $e) {
            $this->addFlash('error', 'Could not update quantity');
            throw new Exception('Item quantity not added: ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_cart');
    }

    /**
     * Decreases quantity of a cart item
     *
     * @param int $cartItemId ID of the cart item
     * @return Response Redirects to cart page
     * @throws Exception If quantity cannot be updated
     */
    #[Route('/cart/minusQuantity/{cartItemId<\d+>}', name: 'minus_quantity_item')]
    #[IsGranted('ROLE_CUSTOMER')]
    public function minusItemQuantity(int $cartItemId): Response
    {
        try {
            $cart = $this->appContext->getCurrentCart();
            $this->cartItemService->minusQuantity($cartItemId);
            $this->cartCalculatorService->updateCartTotals($cart);
            ;

        } catch (Exception $e) {
            $this->addFlash('error', 'Could not update quantity');
            throw new Exception('Item quantity not deduced: ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_cart');
    }

    /**
     * Handles the checkout process
     *
     * @param Request $request HTTP request
     * @return Response Checkout page or order confirmation
     * @throws Exception If checkout fails
     */
    #[Route('cart/checkout', name: 'cart-checkout')]
    #[IsGranted('ROLE_CUSTOMER')]
    public function checkout(Request $request): Response
    {
        $customer = $this->appContext->getCurrentCustomer();
        $orderDetails = new OrderDetails($customer);
        $cart = $this->appContext->getCurrentCart();

        if (!$cart->getTotal() > 0) {
            $this->addFlash('error', 'Cart is Empty');
            return $this->redirectToRoute('app_cart');
        }

        $form = $this->createForm(OrderDetailsFormType::class, $orderDetails);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $order = $this->orderService->createOrder($customer, $cart, $orderDetails);

                $this->addFlash('success', 'Order placed successfully');
                $this->eventDispatcher->dispatch(
                    new OrderPlacedEvent($order),
                    OrderPlacedEvent::class
                );

                return $this->redirectToRoute('app_order');

            } catch (Exception $e) {
                $this->addFlash('error', 'Checkout failed');
                throw new Exception('Could not checkout: ' . $e->getMessage());
            }
        }

        return $this->render('cart/checkout.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
        ]);
    }
}
