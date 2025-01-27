<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
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

    #[ORM\Column]
    private ?int $discount = 0;

    #[ORM\OneToOne(inversedBy: 'cart', cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    /**
     * @var Collection<int, CartItem>
     */
    #[ORM\OneToMany(
        targetEntity: CartItem::class,
        mappedBy: 'cart',
        cascade: ['persist', 'remove'],
        orphanRemoval: false,
        fetch: 'EXTRA_LAZY'
    )]
    private Collection $cartItems;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
        $this->total = 0;
        $this->quantity = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function calculateQuantity()
    {
        $total = array_reduce($this->cartItems->toArray(), function ($q, $item) {
            return $q + $item->getQuantity();
        }, 0);

        return $total;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function resetQuantity(): static
    {
        $this->quantity = $this->calculateQuantity();

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function calculateTotal(): ?int
    {
        $total = array_reduce($this->cartItems->toArray(), function ($sum, $item) {
            return $sum + $item->getTotal();
        }, 0);

        return $total;
    }

    public function setTotal($total): static
    {
        $this->total = $total;

        return $this;
    }

    public function resetTotal()
    {
        $this->total = $this->calculateTotal();

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): static
    {
        $this->discount = $discount;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): static
    {
        $this->customer = $customer;
        $customer->setCart($this);

        return $this;
    }

    public function resetCart()
    {
        $this->setDiscount(0);
        $this->setQuantity(0);

        foreach ($this->cartItems as $item) {
            $this->removeCartItem($item);
        }
    }

    /**
     * @return Collection<int, CartItem>
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): static
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems->add($cartItem);
            $cartItem->setCart($this);
        }
        $this->resetTotal();
        $this->resetQuantity();

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): static
    {
        if ($this->cartItems->removeElement($cartItem)) {
            // set the owning side to null (unless already changed)
            if ($cartItem->getCart() === $this) {
                $cartItem->setCart(null);
            }
        }
        $this->resetTotal();
        $this->resetQuantity();

        return $this;
    }
}
