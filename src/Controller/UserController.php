<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UserController extends AbstractController
{
    use TargetPathTrait;

    public function __construct(private UserRepository $userRepository, private UserService $userService)
    {
    }

    #[Route('/admin/user/delete/{id<\d+>}', name: 'adminuser')]
    public function delete(int $id, Request $request): Response
    {
        $this->userService->deleteUser($id);

        $this->addFlash('success', 'User deleted ' . $id);
        $session = $request->getSession();

        $targetPath = $this->getTargetPath($session, 'main');
        if ($targetPath) {
            return $this->redirect($targetPath);
        }

        return $this->redirectToRoute('admin_customer');
    }



}
