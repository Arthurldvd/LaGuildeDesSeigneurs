<?php

namespace App\Service;

use App\Entity\Character;

interface CharacterServiceInterface
{
    // Creates the character
    public function create(string $data);

    // Checks if the entity has been well filled
    public function isEntityFilled(Character $character);

    // Submits the data to hydrate the object
    public function submit(Character $character, $formName, $data);

    // Finds all the characters
    public function findAll();

    // Modifies the character
    public function update(Character $character, string $data);

    public function delete(Character $character);

    public function serializeJson($object);

    public function findByIntelligence(int $intelligence);
    // Finds all the characters paginated
    public function findAllPaginated($query);

    public function setLinks($object);
}
