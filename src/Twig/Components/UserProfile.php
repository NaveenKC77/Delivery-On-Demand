<?php

namespace App\Twig\Components;

use App\Entity\User as EntityUser;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class UserProfile
{
    public EntityUser $user;
}
