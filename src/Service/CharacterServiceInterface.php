<?php
namespace App\Service;
use App\Entity\Character;
interface CharacterServiceInterface
{
    // Creates the character
    public function create();

    // Finds all the characters
    public function findAll();

    // Modifies the character
    public function update(Character $character);

    public function delete(Character $character);

}