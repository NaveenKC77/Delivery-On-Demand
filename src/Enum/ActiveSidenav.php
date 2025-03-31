<?php

namespace App\Enum;

/**
 * Used to track active page for the sidenav
 */
enum ActiveSidenav: string
{
    case PROFILE = "profile";
    case ORDERS = "order";
    case WISHLIST = "wishlist";
    case NOTIFICATION = "notification";
}
