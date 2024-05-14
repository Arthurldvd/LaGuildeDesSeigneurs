<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Building;
use App\Service\BuildingServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class BuildingController extends AbstractController
{

    public function __construct(
        private BuildingServiceInterface $buildingService,
    ) {}

    #[
        Route('/buildings/', name: 'app_building_create', methods: ['POST'])
    ]
    #[OA\RequestBody(
        request: "Building",
        description: "Data for the Building",
        required: true,
        content: new OA\JsonContent(
            type: Building::class,
            example: [
                "name" => "Castle",
                "slug" => "castle",
                "caste" => "Fortress",
                "strength" => 200,
                "image" => "/buildings/castle.jpg",
                "note" => 5,
                "identifier" => "uniqueidentifier"
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Returns the Building',
        content: new Model(type: Building::class)
    )]
    #[OA\Response(
        response: 403,
        description: 'Access denied'
    )]
    #[OA\Tag(name: 'Building')]
    public function create(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('buildingCreate', null);
        $building = $this->buildingService->create($request->getContent());
        $response = JsonResponse::fromJsonString($this->buildingService->serializeJson($building), JsonResponse::HTTP_CREATED);
        $url = $this->generateUrl(
            'app_building_display',
            ['identifier' => $building->getIdentifier()]
        );
        $response->headers->set('Location', $url);
        return $response;
    }

    #[
        Route(
            '/buildings/{identifier}',
            requirements: ['identifier' => '^([a-z0-9]{40})$'],
            name: 'app_building_display',
            methods: ['GET']
        )
    ]
    #[OA\Parameter(
        name: 'identifier',
        in: 'path',
        description: 'Identifier for the Building',
        schema: new OA\Schema(type: 'string'),
        required: true
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the Building',
        content: new OA\JsonContent(ref: new Model(type: Building::class))
    )]
    #[OA\Response(
        response: 403,
        description: 'Access denied'
    )]
    #[OA\Response(
        response: 404,
        description: 'Not found'
    )]
    #[OA\Tag(name: 'Building')]
    public function display(
         #[MapEntity(expr: 'repository.findOneByIdentifier(identifier)')]
         Building $building
    ): JsonResponse
    {
        $this->denyAccessUnlessGranted('buildingDisplay', $building);
        return JsonResponse::fromJsonString($this->buildingService->serializeJson($building));
    }

    #[
        Route('/buildings/', name: 'app_building_index', methods: ['GET'])
    ]
    #[OA\Response(
        response: 200,
        description: 'Returns an array of Buildings',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Building::class))
        )
    )]
    #[OA\Response(
        response: 403,
        description: 'Access denied'
    )]
    #[OA\Tag(name: 'Building')]
    public function index(): JsonResponse
    {
        $this->denyAccessUnlessGranted('buildingIndex', null);
        $buildings = $this->buildingService->findAll();
        return JsonResponse::fromJsonString($this->buildingService->serializeJson($buildings));
    }

    #[
        Route('/buildings/{identifier}', requirements: ['identifier' => '^([a-z0-9]{40})$'], name: 'app_building_update', methods: ['PUT'])
    ]
    #[OA\Parameter(
        name: 'identifier',
        in: 'path',
        description: 'Identifier for the Building',
        schema: new OA\Schema(type: 'string'),
        required: true
    )]
    #[OA\RequestBody(
        request: "Building",
        description: "Data for the Building",
        required: true,
        content: new OA\JsonContent(
            type: Building::class,
            example: [
                "name" => "Fortress",
                "slug" => "fortress",
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
    #[OA\Tag(name: 'Building')]
    public function update(Request $request, Building $building): JsonResponse
    {
        $this->denyAccessUnlessGranted('buildingUpdate', $building);
        $building = $this->buildingService->update($building, $request->getContent());
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[
        Route('/buildings/{identifier}', requirements: ['identifier' => '^([a-z0-9]{40})$'], name: 'app_building_delete', methods: ['DELETE'])
    ]
    #[OA\Parameter(
        name: 'identifier',
        in: 'path',
        description: 'Identifier for the Building',
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
    #[OA\Tag(name: 'Building')]
    public function delete(Building $building): JsonResponse
    {
        $this->denyAccessUnlessGranted('buildingDelete', $building);
        $this->buildingService->delete($building);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
