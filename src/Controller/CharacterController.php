<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Character;
use App\Service\CharacterServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class CharacterController extends AbstractController
{
    #[
        Route(
            '/characters/',
            name: 'app_character_index',
            methods: ['GET']
        )
    ]
    #[OA\Response(
        response: 200,
        description: 'Returns an array of Characters',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Character::class))
        )
    )]
    #[OA\Response(
        response: 403,
        description: 'Access denied'
    )]
    #[OA\Tag(name: 'Character')]
    public function index(): JsonResponse
    {
        return JsonResponse::fromJsonString($this->characterService->findAllJson());
    }
    #[
        Route(
            '/characters/{identifier}',
            name: 'app_character_display',
            requirements: ['identifier' => '^([a-z0-9]{40})$'],
            methods: ['GET']
        )
    ]
    #[OA\Parameter(
        name: 'identifier',
        in: 'path',
        description: 'Identifier for the Character',
        schema: new OA\Schema(type: 'string'),
        required: true
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the Character',
        content: new OA\JsonContent(ref: new Model(type: Character::class))
    )]
    #[OA\Response(
        response: 403,
        description: 'Access denied'
    )]
    #[OA\Response(
        response: 404,
        description: 'Not found'
    )]
    #[OA\Tag(name: 'Character')]
    public function display(
        #[MapEntity(expr: 'repository.findOneByIdentifier(identifier)')]
        Character $character
    ): JsonResponse {
        $this->denyAccessUnlessGranted('characterDisplay', $character);
        return JsonResponse::fromJsonString($this->characterService->serializeJson($character));
    }

    #[Route('/characters/', name: 'app_character_create', methods: ['POST'])]
    #[OA\RequestBody(
        request: "Character",
        description: "Data for the Character",
        required: true,
        content: new OA\JsonContent(
            type: Character::class,
            example: [
                "kind" => "Dame",
                "name" => "Maeglin",
                "surname" => "Oeil vif",
                "caste" => "Archer",
                "knowledge" => "Nombres",
                "intelligence" => 120,
                "strength" => 120,
                "image" => "/dames/maeglin.webp"
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Returns the Character',
        content: new Model(type: Character::class)
    )]
    #[OA\Response(
        response: 403,
        description: 'Access denied'
    )]
    #[OA\Tag(name: 'Character')]
    public function create(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterCreate', null);

        $character = $this->characterService->create($request->getContent());

        $response = JsonResponse::fromJsonString($this->characterService->serializeJson($character), JsonResponse::HTTP_CREATED);
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
            name: 'app_character_update',
            requirements: ['identifier' => '^([a-z0-9]{40})$'],
            methods: ['PUT']
        )
    ]
    #[OA\Parameter(
        name: 'identifier',
        in: 'path',
        description: 'Identifier for the Character',
        schema: new OA\Schema(type: 'string'),
        required: true
    )]
    #[OA\RequestBody(
        request: "Character",
        description: "Data for the Character",
        required: true,
        content: new OA\JsonContent(
            type: Character::class,
            example: [
                "kind" => "Seigneur",
                "name" => "Gorthol",
            ]
        )
    )]
    #[OA\Response(
        response: 204,
        description: 'No content'
    )]
    #[OA\Response(
        response: 403,
        description: 'Access denied'
    )]
    #[OA\Response(
        response: 404,
        description: 'Not found'
    )]
    #[OA\Tag(name: 'Character')]
    public function update(Request $request, Character $character): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterUpdate', $character);
        $character = $this->characterService->update($character, $request->getContent());
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[
        Route(
            '/characters/{identifier}',
            name: 'app_character_delete',
            requirements: ['identifier' => '^([a-z0-9]{40})$'],
            methods: ['DELETE']
        )
    ]
    #[OA\Parameter(
        name: 'identifier',
        in: 'path',
        description: 'Identifier for the Character',
        schema: new OA\Schema(type: 'string'),
        required: true
    )]
    #[OA\Response(
        response: 204,
        description: 'No content'
    )]
    #[OA\Response(
        response: 403,
        description: 'Access denied'
    )]
    #[OA\Response(
        response: 404,
        description: 'Not found'
    )]
    #[OA\Tag(name: 'Character')]
    public function delete(Character $character): JsonResponse
    {
        $this->denyAccessUnlessGranted('characterDelete', null);
        $this->characterService->delete($character);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    public function __construct(
        private CharacterServiceInterface $characterService
    ) {
    }
}
