<?php

namespace App\Service;

use App\Entity\Building;

interface BuildingServiceInterface
{
    // Creates the building
    public function create(string $data);
    public function isEntityFilled(Building $building);
    public function submit(Building $building, $formName, $data);

    // Finds all the buildings
    public function findAll();

    // Modifies the building
    public function update(Building $building, string $data);

    public function delete(Building $building);

    // Serializes the object(s)
    public function serializeJson($object);

    public function setLinks($object);
}
