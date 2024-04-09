<?php
namespace App\Service;
interface CharacterServiceInterface
{
    // Creates the character
    public function create();

    // Finds all the characters
    public function findAll();
}