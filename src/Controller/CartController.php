<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\User;
use App\Services\CartItemService;
use App\Services\CartService;
use App\Services\ProductService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CartController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private CartItemService $cartItemService,
        private UserService $userService,
        private ProductService $productService,
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
            $this->addFlash('success', 'Product'.$product->getName().'successfully added');
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
            $this->addFlash('success', 'Item'.$cartItem->getProduct()->getName().'successfully removed');
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
}
