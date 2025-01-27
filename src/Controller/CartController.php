<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Order;
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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CartController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private CartItemService $cartItemService,
        private UserService $userService,
        private ProductService $productService,
        private OrderService $orderService,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }
    #[Route('/cart', name: 'app_cart')]
    #[IsGranted('ROLE_CUSTOMER')]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new \Exception('wrong type of User passed');
        }
        $customerId = $user->getCustomer()->getId();
        $cart = $this->cartService->getCartFromCustomerId($customerId);

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/cart/add/{id<\d+>}', name: 'add_item_to_cart')]
    public function addItem(int $id): Response
    {
        $product = $this->productService->getOneById($id);
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new \Exception('');
        }
        try {
            $cart = $user->getCustomer()->getCart();
            $cartItem = new CartItem($product, $cart);
            $this->cartService->addCartItem($cartItem);
            $this->addFlash('success', 'Product' . $product->getName() . 'successfully added');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id<\d+>}', name: 'remove_item_from_cart')]
    public function removeItem(int $id): Response
    {
        try {
            $cartItem = $this->cartItemService->getOneById($id);
            $this->cartService->removeCartItem($cartItem);
            $this->addFlash('success', 'Item' . $cartItem->getProduct()->getName() . 'successfully removed');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/plusQuantity/{id<\d+>}', name: 'add_quantity_item')]
    public function addItemQuantity(int $id): Response
    {
        try {
            $this->cartItemService->plusQuantity($id);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/minusQuantity/{id<\d+>}', name: 'minus_quantity_item')]
    public function minusItemQuantity(int $id): Response
    {
        try {
            $this->cartItemService->minusQuantity($id);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('cart/checkout', 'cart-checkout')]

    public function checkout(Request $request)
    {

        $customer = $this->getUser()->getCustomer();

        $orderDetails = new OrderDetails();

        $orderDetails->setShippingAddress($customer->getAddress());
        $orderDetails->setPostalAddress($customer->getAddress());
        $orderDetails->setEmail($customer->getUser()->getEmail());

        $cart = $customer->getCart();

        $form = $this->createForm(OrderDetailsFormType::class, $orderDetails);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $orderDetails = $form->getData();
            $order = new Order();

            // Set the order details and subtotal
            $order->setOrderDetails($orderDetails);
            $order->setSubtotal($cart->getTotal());

            // Add cart items to the order
            foreach ($cart->getCartItems() as $item) {
                $order->addCartItem($item);
            }

            // Automatically calculate tax and total based on subtotal
            $order->setTax($order->calculateTax(10)); // Assuming a 10% tax rate
            $order->setTotal($order->calculateTotal());
            $order->setCustomer($customer);
            // Reset the cart
            $cart->resetCart();

            // Add a success flash message
            $this->addFlash('success', 'Order Confirmed');

            try {
                // persist order
                $this->orderService->add($order);

                // raise orderPlacedEvent
                $event = new OrderPlacedEvent($order);
                $this->eventDispatcher->dispatch($event, OrderPlacedEvent::class);
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }


            return $this->redirectToRoute('app_cart');


        }

        return $this->render('cart/checkout.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
        ]);
    }
}
