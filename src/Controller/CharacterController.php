<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Character;
use App\Service\CharacterServiceInterface;
use Symfony\Component\HttpFoundation\Request;
class CharacterController extends AbstractController
{
    #[
        Route('/characters/',
            name: 'app_character_index',
            methods: ['GET'])
    ]
    public function index(): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterIndex', null);
        $characters = $this->characterService->findAll();
        return new JsonResponse($characters);
    }

    #[
        Route(
            '/characters/{identifier}',
            name: 'app_character_display',
            requirements: ['identifier' => '^([a-z0-9]{40})$'],
            methods: ['GET']
        )
    ]
    public function display(Character $character): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterDisplay', $character);
        return new JsonResponse($character->toArray());
    }

    #[Route('/characters/', name: 'app_character_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterCreate', null);

        $character = $this->characterService->create($request->getContent());

        $response = new JsonResponse($character->toArray(), JsonResponse::HTTP_CREATED);
        $url = $this->generateUrl(
        'app_character_display',
            ['identifier' => $character->getIdentifier()]
        );
        $response->headers->set('Location', $url);
        return $response;
    }

    #[
        Route('/characters/{identifier}',
            name: 'app_character_update',
            requirements: ['identifier' => '^([a-z0-9]{40})$'],
            methods: ['PUT'])
    ]
    public function update(Request $request, Character $character): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterUpdate', $character);
        $character = $this->characterService->update($character, $request->getContent());
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[
        Route('/characters/{identifier}',
            name: 'app_character_delete',
            requirements: ['identifier' => '^([a-z0-9]{40})$'],
            methods: ['DELETE'])
    ]
    public function delete(Character $character): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterDelete', null);
        $this->characterService->delete($character);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    public function __construct(
        private CharacterServiceInterface $characterService
    ) {}
}