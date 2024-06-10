<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
class UserController extends AbstractController
{
    #[Route('
        /signin',
        name: 'app_signin',
        methods: ['POST']
    )]
    public function signin(): JsonResponse
    {
        $user = $this->getUser();
        return new JsonResponse([
        'username' => $user->getUserIdentifier(),
        'roles' => $user->getRoles(),
        ]);
    }
}