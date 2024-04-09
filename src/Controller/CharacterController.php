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

    #[Route('/characters/', name: 'app_character_create', methods: ['POST'])]
    public function create(): JsonResponse
    {
        $character = $this->characterService->create();
        $response = new JsonResponse($character->toArray(), JsonResponse::HTTP_CREATED);
        $url = $this->generateUrl(
            'app_character_display',
            ['identifier' => $character->getIdentifier()]
        );
        $response->headers->set('Location', $url);
        return $response;
    }

    #[
                Route(
                    '/characters/{identifier}',
                    requirements: ['identifier' => '^([a-z0-9]{40})$'],
                    name: 'app_character_display',
                    methods: ['GET']
                )
            ]
            public function display(Character $character): JsonResponse
            {
                return new JsonResponse($character->toArray());
            }

}