<?php

//src/Service/BuildingService.php
namespace App\Service;
use DateTime; 
use App\Entity\Building;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BuildingRepository;
use App\Form\BuildingType;
use LogicException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Cocur\Slugify\Slugify;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BuildingService implements BuildingServiceInterface
{
    public function __construct(
            private EntityManagerInterface $em,
            private BuildingRepository $buildingRepository,
            private FormFactoryInterface $formFactory,
            private ValidatorInterface $validator,
        ) {}

        public function findAll(): array
        {
            $buildingsFinal = array();
            $buildings = $this->buildingRepository->findAll();
            foreach ($buildings as $building) {
                $buildingsFinal[] = $building->toArray();
            }
            return $buildingsFinal;
        }
    // Creates the building
    public function create(string $data): Building
    {
        $building = new Building();
        $this->submit($building, BuildingType::class, $data);
        $building->setSlug((new Slugify())->slugify($building->getName()));
        
       
        $building->setIdentifier(hash('sha1', uniqid()));
        $building->setCreatedAt(new DateTime());
        $building->setUpdatedAt(new \DateTime());
        $this->isEntityFilled($building);
        $this->em->persist($building);
        $this->em->flush();
        return $building;
    }

    public function update(Building $building, string $data): Building
    {
        $this->submit($building, BuildingType::class, $data);
        $building->setSlug((new Slugify())->slugify($building->getName()));
        $building->setUpdatedAt(new \DateTime());
        $building->setUpdatedAt(new \DateTime());
        $this->em->persist($building);
        $this->em->flush();
        return $building;
    }

    public function delete(Building $building)
    {
        $this->em->remove($building);
        $this->em->flush();
    }

    public function submit(Building $building, $formName, $data)
        {
        $dataArray = is_array($data) ? $data : json_decode($data, true);
        // Bad array
        if (null !== $data && !is_array($dataArray)) {
        throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);
        }
        // Submits form
        $form = $this->formFactory->create($formName, $building, ['csrf_protection' => false]);
        $form->submit($dataArray, false);
        $errors = $form->getErrors();
        foreach ($errors as $error) {
        $errorMsg = 'Error ' . get_class($error->getCause());
        $errorMsg .= ' --> ' . $error->getMessageTemplate();
        $errorMsg .= ' ' . json_encode($error->getMessageParameters());
        throw new LogicException($errorMsg);
        }
    }
    public function isEntityFilled(Building $building)
        {
        $errors = $this->validator->validate($building);
        if (count($errors) > 0) {
            $errorMsg = (string) $errors . 'Wrong data for Entity -> ';
            $errorMsg .= json_encode($building->toArray());
            throw new UnprocessableEntityHttpException($errorMsg);
        }
        }
}
