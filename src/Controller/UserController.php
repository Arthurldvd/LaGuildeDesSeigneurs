<?php

namespace App\Controller;

use App\Entity\Building;
use App\Entity\User;
use App\Service\UserServiceInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(
        private UserServiceInterface $userService
    )
    {
    }

    #[OA\RequestBody(
        request: "User",
        description: "Data for the User",
        required: true,
        content: new OA\JsonContent(
            type: User::class,
            example: [
                "username" => "contact@example.com",
                "password" => "StrongPassword*"
            ]
        )
    )]
    public function index(): JsonResponse
    {
        return new JsonResponse();
    }

    #[Route('/signin',
        name: 'app_signin',
        methods: ['POST']
    )]
    #[OA\RequestBody(
        request: "username",
        description: "Username to sign in",
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "username",
                    description: "Username to sign in",
                    type: "string",
                    example: "contact@example.com"
                ),
                new OA\Property(
                    property: "password",
                    description: "Password to sign in",
                    type: "string",
                    example: "StrongPassword*"
                )
            ],
            type: "object"
        )
    )]
    #[OA\RequestBody(
        request: "password",
        description: "Password to sign in",
        required: true,
        content: new OA\JsonContent(
            type: "string",
            example: "StrongPassword*"
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns a JWT',
        content: new OA\JsonContent(
            type: 'string',
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Not found'
    )]
    #[OA\Tag(name: 'User')]
    public function signin(): JsonResponse
    {
        $user = $this->getUser();

        if (null !== $user) {
            return new JsonResponse([
                'token' => $this->userService->getToken($user),
            ]);
        }

        return new JsonResponse([
            'error' => 'User not found',
        ]);
    }
}