<?php

namespace App\Twig\Components;

use App\Entity\Product as EntityProduct;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Product
{
    public EntityProduct $product;
}
