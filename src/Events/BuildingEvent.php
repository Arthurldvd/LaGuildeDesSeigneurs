<?php

//src/Event/.php

namespace App\Events;

use App\Entity\Building;
use Symfony\Contracts\EventDispatcher\Event;

class BuildingEvent extends Event
{
    // Constante pour le nom de l'event, nommage par convention
    public const BUILDING_UPDATED = 'app.building.updated';
    // Injection de l'objet
    public function __construct(
        protected Building $building
    ) {
    }


    // Getter pour l'objet

    public function getBuilding(): Building
    {
        return $this->building;
    }
}
