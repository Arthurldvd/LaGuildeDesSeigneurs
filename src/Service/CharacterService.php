<?php

namespace App\Service;

use DateTime; // on ajoute le use pour supprimer le \ dans setCreation()
use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CharacterRepository;
use App\Form\CharacterType;
use LogicException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Cocur\Slugify\Slugify;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Events\CharacterEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;

use function Symfony\Component\Clock\now;

class CharacterService implements CharacterServiceInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private CharacterRepository $characterRepository,
        private FormFactoryInterface $formFactory,
        private ValidatorInterface $validator,
        private EventDispatcherInterface $dispatcher,
        private PaginatorInterface $paginator,
    ) {
    }


    // Serializes the objet(s)
    public function serializeJson($object)
    {
        $encoders = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ];
        $normalizers = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([new DateTimeNormalizer(), $normalizers], [$encoders]);
        $this->setLinks($object);
        return $serializer->serialize($object, 'json');
    }
    public function create(string $data): Character
    {
        $character = new Character();

        $this->submit($character, CharacterType::class, $data);
        $event = new CharacterEvent($character);
        $this->dispatcher->dispatch($event, CharacterEvent::CHARACTER_CREATED);
        $character->setSlug((new Slugify())->slugify($character->getName()));

        $character->setIdentifier(hash('sha1', uniqid()));
        $character->setCreation(new DateTime());
        $character->setModification(new DateTime());

        $this->isEntityFilled($character);

        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }

    public function update(Character $character, string $data): Character
    {
        $this->submit($character, CharacterType::class, $data);
        $character->setSlug((new Slugify())->slugify($character->getName()));

        $character->setModification(new DateTime());
        $this->isEntityFilled($character);

        $this->em->persist($character);
        $this->em->flush();
        return $character;
    }

    public function findAll(): array
    {
        return $this->characterRepository->findAll();
    }

    public function delete(Character $character): void
    {
        $this->em->remove($character);
        $this->em->flush();
    }

    public function isEntityFilled(Character $character): void
    {
        $errors = $this->validator->validate($character);
        if (count($errors) > 0) {
            $errorMsg = 'Wrong data for Entity -> ';

            foreach ($errors as $error) {
                $errorMsg .= $error->getMessage() . ', ';
            }

            $errorMsg = rtrim($errorMsg, ', ');

            $entityData = $this->serializeJson($character);
            $errorMsg .= '. Entity Data: ' . json_encode($entityData);

            throw new UnprocessableEntityHttpException($errorMsg);
        }
    }
    public function submit(Character $character, $formName, $data): void
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true);

        if (null !== $data && !is_array($dataArray)) {
            throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);
        }

        $form = $this->formFactory->create($formName, $character, ['csrf_protection' => false]);
        $form->submit($dataArray, false);

        $errors = $form->getErrors();
        foreach ($errors as $error) {
            $errorMsg = 'Error ' . get_class($error->getCause());
            $errorMsg .= ' --> ' . $error->getMessageTemplate();
            $errorMsg .= ' ' . json_encode($error->getMessageParameters());
            throw new LogicException($errorMsg);
        }
    }
    public function findAllPaginated($query): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        return $this->paginator->paginate(
            $this->findAll(), // On appelle la même requête
            $query->getInt('page', 1), // 1 par défaut
            min(100, $query->getInt('size', 10)) // 10 par défaut et 100 maximum
        );
    }

    
    public function setLinks($object)
        {
            if($object instanceof SlidingPagination) {
             // Si oui, on boucle sur les items
             foreach ($object->getItems() as $item) {
                $this->setLinks($item);
             }
             return;
             }
        }
}
