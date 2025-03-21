<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Customer;
use App\Entity\OrderDetails;
use App\Entity\User;
use App\Event\Events\OrderPlacedEvent;
use App\Form\OrderDetailsFormType;
use App\Services\CartItemService;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


/**
 * Summary of CartController
 * controls cart page
 */
class CartController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private CartItemService $cartItemService,
        private UserService $userService,
        private ProductService $productService,
        private OrderService $orderService,
        private EventDispatcherInterface $eventDispatcher,
        private Security $security,
    ) {
    }
    //helper method to get cart from current logged in user
    private function getCart(): Cart|null
    {

        $cache = new FilesystemAdapter();
        // getting authorized user
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new \Exception('wrong type of User passed');
        }
        // checking if user is customer and getting its customer id and then cart associated
        $customer = $user->getCustomer();


        if (!$customer instanceof Customer) {
            throw new \Exception('wrong type of User passed');
        }
        // $cart = $cache->get('Cart',function (ItemInterface $item)use($customer): Cart|null{
        //     $item->expiresAfter(10);

        //     return $customer->getCart();

        // });

        $cart = $customer->getCart();
        // $cart = $customer->getCart();
        return $cart;
    }

    /**
     * loads cart for each customer
     */
    #[Route('/cart', name: 'app_cart')]
    #[IsGranted('ROLE_CUSTOMER')]
    public function index(): Response
    {
        $cart = $this->getCart();

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * add item to cart
     */
    #[Route('/cart/add/{productId<\d+>}', name: 'add_item_to_cart')]
    public function addItem(int $productId): Response
    {
        $product = $this->productService->getOneById($productId);

        try {
            $cart = $this->getCart();
            $cartItem = new CartItem($product, $cart);

            if ($this->cartService->checkItemExists($cart, $cartItem)) {
                $this->addFlash('error', 'Item Already Exists in the Cart');
                return $this->redirectToRoute('app_cart');
            }
            $this->cartService->addCartItem($cart, $cartItem);


        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{cartItemId<\d+>}', name: 'remove_item_from_cart')]
    public function removeItem(int $cartItemId): Response
    {
        try {
            $cart = $this->getCart();
            $cartItem = $this->cartItemService->getOneById($cartItemId);
            $this->cartService->removeCartItem($cart, $cartItem);
            $this->addFlash('success', 'Item' . $cartItem->getProduct()->getName() . 'successfully removed');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/plusQuantity/{cartItemId<\d+>}', name: 'add_quantity_item')]
    public function addItemQuantity(int $cartItemId): Response
    {
        try {
            $cart = $this->getCart();
            $this->cartItemService->plusQuantity($cartItemId);
            //reset cart total
            $this->cartService->resetCartNumbers($cart);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/minusQuantity/{cartItemId<\d+>}', name: 'minus_quantity_item')]
    public function minusItemQuantity(int $cartItemId): Response
    {
        try {
            $cart = $this->getCart();
            $this->cartItemService->minusQuantity($cartItemId);
            //reset cart total
            $this->cartService->resetCartNumbers($cart);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('cart/checkout', 'cart-checkout')]

    public function checkout(Request $request)
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new \Exception('wrong type of User passed');
        }
        $customer = $user->getCustomer();

        $orderDetails = new OrderDetails($customer);
        $cart = $customer->getCart();

        $form = $this->createForm(OrderDetailsFormType::class, $orderDetails);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $orderDetails = $form->getData();

            try {
                //create new order and returns that order
                $order = $this->orderService->createOrder($customer, $cart, $orderDetails);

                // Add a success flash message
                $this->addFlash('success', 'Order Confirmed');

                // raise orderPlacedEvent
                $event = new OrderPlacedEvent($order);
                $this->eventDispatcher->dispatch($event, OrderPlacedEvent::class);

                return $this->redirectToRoute('app_main');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }

            return $this->redirectToRoute('app_order');
        }

        return $this->render('cart/checkout.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
        ]);
    }
}
