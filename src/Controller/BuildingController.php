<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Building;
use App\Service\BuildingServiceInterface;


class BuildingController extends AbstractController
{

    public function __construct(
        private BuildingServiceInterface $buildingService
    ) {}

    #[Route('/buildings/', name: 'app_building_create', methods: ['POST'])]
    public function create(): JsonResponse
    {
        $this->denyAccessUnlessGranted('buildingCreate', null);
        $building = $this->buildingService->create();
        $response = new JsonResponse($building->toArray(), JsonResponse::HTTP_CREATED);
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
    public function display(Building $building): JsonResponse
    {
        $this->denyAccessUnlessGranted('buildingDisplay', $building);
        return new JsonResponse($building->toArray());
    }

    //  INDEX
    #[
        Route('/buildings/',
        name: 'app_building_index',
        methods: ['GET'])
    ]
    public function index(): JsonResponse
    {
        $this->denyAccessUnlessGranted('buildingIndex', null);
        $buildings = $this->buildingService->findAll();
        return new JsonResponse($buildings);
    }

    #[
        Route('/buildings/{identifier}',
        requirements: ['identifier' => '^([a-z0-9]{40})$'],
        name: 'app_building_update',
        methods: ['PUT'])
    ]
    public function update(Building $building): JsonResponse
    {
        $this->denyAccessUnlessGranted('buildingUpdate', $building);
        $this->buildingService->update($building);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[
        Route('/buildings/{identifier}',
        requirements: ['identifier' => '^([a-z0-9]{40})$'],
        name: 'app_building_delete',
        methods: ['DELETE'])
    ]
    public function delete(Building $building): JsonResponse
    {
        $this->denyAccessUnlessGranted('buildingDelete', $building);
        $building = $this->buildingService->delete($building);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}