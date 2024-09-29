<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    

    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        
        // if(isset($_GET['msg'])){
        //     $msg =$_GET['msg'];
        // }


        
        
        return $this->render('main/homepage.html.twig', [
            
            // 'msg'=>$msg
        ]);
    }
}
