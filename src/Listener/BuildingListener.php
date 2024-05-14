<?php

// src/Listener/CharacterListener.php

namespace App\Listener;

use App\Events\BuildingEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BuildingListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        // Événements que l'on souhaite écouter
        return array(
            BuildingEvent::BUILDING_UPDATED => 'buildingUpdated', // Nom de la méthode appelée
        );
    }
    // Méthode appelée lorsque l'objet est créé
    public function buildingUpdated($event): void
    {
        $building = $event->getBuilding();
        $building->setStrength(0);
    }
}
