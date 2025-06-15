<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
final class UserController extends AbstractController
{

    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserService $userService): Response
    {
        $users = $userService->getAllUsers();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(UserService $userService, int $id): Response
    {
        $id = $userService->getUserById($id);
        return $this->render('user/show.html.twig', [
            'user' => $id,
        ]);
    }
}
