<?php
namespace App\Service;
use App\Entity\Building;

interface BuildingServiceInterface
{
    // Creates the character
    public function create();

    // Finds all the characters
    public function findAll();

    // Modifies the character
    public function update(Building $building);

    public function delete(Building $building);

}