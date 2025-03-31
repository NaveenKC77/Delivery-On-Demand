<?php

namespace App\Twig\Components;

use App\Enum\ActiveSidenav;
use App\Services\AppContextInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]

class ProfileSidenav
{
    /**
     * Unread Notifications Count to display in sidenav
     * @var int
     */
    public int $unreadCount =0; 
    /**
     * Keep track of the active page to display on sidenav
     * @var string
     */
    public ActiveSidenav $active;
    public function __construct(private AppContextInterface $appContext){
        $this->unreadCount = $this->appContext->getUnreadNotificationsCount();
    }

  
}
