<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Character;
use App\Service\CharacterServiceInterface;

class CharacterController extends AbstractController
{

    public function __construct(
        private CharacterServiceInterface $characterService
    ) {}
    
    #[Route('/characters', name: 'app_character_create', methods: ['POST'])]
    public function create(): JsonResponse
    {
        $character = $this->characterService->create();
        return new JsonResponse($character->toArray(), JsonResponse::HTTP_CREATED);
    }
}