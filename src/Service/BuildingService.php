<?php

//src/Service/CharacterService.php
namespace App\Service;
use DateTime; 
use App\Entity\Building;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BuildingRepository;

class BuildingService implements BuildingServiceInterface
{
    public function __construct(
            private EntityManagerInterface $em,
            private BuildingRepository $buildingRepository
        ) {}

        public function findAll(): array
        {
            $buildingsFinal = array();
            $buildings = $this->buildingRepository->findAll();
            foreach ($buildings as $building) {
                $buildingsFinal[] = $building->toArray();
            }
            return $buildingsFinal;
        }
    // Creates the character
    public function create(): Building
    {
        $building = new Building();
        $building->setName('Chateau DirenWood');
        $building->setSlug('chateau-direnwood');
        $building->setCaste('ElfeNoir');  
        $building->setCreatedAt(new \DateTime());
        $building->setUpdatedAt(new \DateTime());
        $building->setStrength(15000);
        $building->setImage('/castle/direnwood.webp');
        $building->setNote(3);
        $building->setIdentifier(hash('sha1', uniqid()));
      
        $this->em->persist($building);
        $this->em->flush();
        return $building;
    }

    public function update(Building $building): Building
    {
        $building->setName('Chateau Lenora');
        $building->setSlug('chateau-lenora');
        $building->setCaste('Guerrier');
        $building->setStrength(1000);
        $building->setImage('/castle/lenora.webp');
        $building->setNote(3);
        $building->setUpdatedAt(new \DateTime());
        $this->em->persist($building);
        $this->em->flush();
        return $building;
    }

    public function delete(Building $building)
    {
        $this->em->remove($building);
        $this->em->flush();
    }
}