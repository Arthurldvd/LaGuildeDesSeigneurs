<?php

namespace App\DataFixtures;

use App\Entity\Building;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Character;

class AppFixtures extends Fixture
{
    private Slugify $slugify;
    public function __construct()
    {
        $this->slugify = new Slugify();
    }

    public function load(ObjectManager $manager): void
    {
        // Creates All the Characters from json
        $characters = json_decode(file_get_contents('https://la-guilde-des-seigneurs.com/json/characters.json'), true);
        foreach ($characters as $characterData) {
            $manager->persist($this->setCharacter($characterData));
        }

        $buildings = json_decode(file_get_contents('https://la-guilde-des-seigneurs.com/json/buildings.json'), true);
        foreach ($buildings as $buildingsData) {
            $manager->persist($this->setBuilding($buildingsData));
        }

        $manager->flush();
    }

    public function setCharacter(array $characterData): Character
    {
        $character = new Character();
        foreach ($characterData as $key => $value) {
            $method = 'set' . ucfirst($key); // Construit le nom de la méthode
            if (method_exists($character, $method)) { // Si la méthode existe
                $character->$method($value ?? null); // Appelle la méthode
            }
        }
        $character->setSlug($this->slugify->slugify($characterData['name']));
        $character->setIdentifier(hash('sha1', uniqid()));
        $character->setCreation(new \DateTime());
        $character->setModification(new \DateTime());
        return $character;
    }

    public function setBuilding(array $buildingData): Building
    {
        $building = new Building();
        foreach ($buildingData as $key => $value) {
            $method = 'set' . ucfirst($key); // Construit le nom de la méthode
            if (method_exists($building, $method)) { // Si la méthode existe
                $building->$method($value ?? null); // Appelle la méthode
            }
        }
        $building->setSlug($this->slugify->slugify($buildingData['name']));
        $building->setIdentifier(hash('sha1', uniqid()));
        $building->setCreatedAt(new \DateTime());
        $building->setUpdatedAt(new \DateTime());
        return $building;
    }
}