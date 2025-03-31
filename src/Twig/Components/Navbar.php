<?php

namespace App\Twig\Components;

use App\Services\AppContextInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Navbar
{
    public int $unreadCount =0; 
    public function __construct(private AppContextInterface $appContext){

        try{
            $this->unreadCount = $this->appContext->getUnreadNotificationsCount();

        }
        catch(\Exception $e){
            $this->unreadCount = 0;
        }
    }




}
