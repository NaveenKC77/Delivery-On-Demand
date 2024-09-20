<?php
namespace App\Controller;

use App\Entity\UserLogins;
use App\Repository\DbTestRepository;
use App\Repository\UserLoginsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(UserLoginsRepository $userLoginsRepository): Response
    {
        $objects = $userLoginsRepository->findAll();
        return $this->render('main/index.html.twig', [
            'objects' => $objects,
        ]);
    }
}
