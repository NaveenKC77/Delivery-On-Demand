<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
class CartItem
{
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTotal(): void
    {
        $this->resetTotal();
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = 0;

    #[ORM\Column]
    private ?int $total = 0;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'cartItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cart $cart = null;

    #[ORM\ManyToOne(inversedBy: 'cartItems')]
    private ?Order $order = null;

    public function __construct(Product $product, Cart $cart)
    {
        $this->setProduct($product);
        $this->setCart($cart);
        $this->setQuantity(1);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;
        $this->resetTotal();
        if ($this->cart) {
            $this->cart->resetTotal();
            $this->cart->resetQuantity();
        }

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function calculateTotal(): ?int
    {
        return $this->getProduct()->getPrice() * $this->getQuantity();
    }

    public function resetTotal(): static
    {
        $this->total = $this->calculateTotal();

        return $this;
    }

    public function setTotal($total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        $this->cart = $cart;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): static
    {
        $this->order = $order;

        return $this;
    }
}
