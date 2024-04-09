<?php

//src/Service/CharacterService.php
namespace App\Service;
use DateTime; // on ajoute le use pour supprimer le \ dans setCreation()
use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CharacterRepository;

class CharacterService implements CharacterServiceInterface
{
    public function __construct(
            private EntityManagerInterface $em,
            private CharacterRepository $characterRepository
        ) {}

        public function findAll(): array
        {
            $charactersFinal = array();
            $characters = $this->characterRepository->findAll();
            foreach ($characters as $character) {
                $charactersFinal[] = $character->toArray();
            }
            return $charactersFinal;
        }
    // Creates the character
    public function create(): Character
    {
        $character = new Character();
        $character->setKind('Egorgeur');
        $character->setName('Dagnir');
        $character->setSlug('Tourmenteur');
        $character->setSurname('Égorgeur');
        $character->setCaste('ElfeNoir');
        $character->setKnowledge('Arts');
        $character->setIntelligence(160);
        $character->setStrength(1600);
        $character->setImage('/elfesnoir/dagnir.webp');
        $character->setCreation(new \DateTime());
        $character->setIdentifier(hash('sha1', uniqid()));
        $this->em->persist($character);
        $this->em->flush();
        return $character;
    }
}