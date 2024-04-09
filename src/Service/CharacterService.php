<?php

//src/Service/CharacterService.php
namespace App\Service;
use DateTime; // on ajoute le use pour supprimer le \ dans setCreation()
use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;

class CharacterService implements CharacterServiceInterface
{
    public function __construct(
        private EntityManagerInterface $em
        ) {}
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
        $this->em->persist($character);
        $this->em->flush();
        return $character;
    }
}