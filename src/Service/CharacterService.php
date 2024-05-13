<?php
namespace App\Service;
use DateTime; // on ajoute le use pour supprimer le \ dans setCreation()
use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CharacterRepository;
use function Symfony\Component\Clock\now;
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

class CharacterService implements CharacterServiceInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private CharacterRepository $characterRepository,
        private FormFactoryInterface $formFactory,
        private ValidatorInterface $validator,
    ) {}


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
 return $serializer->serialize($object, 'json');
 }
    public function create(string $data): Character
    {
        $character = new Character();

        $this->submit($character, CharacterType::class, $data);

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
        $charactersFinal = array();
        $characters = $this->characterRepository->findAll();
        foreach ($characters as $character) {
            $charactersFinal[] = $character->toArray();
        }
        return $this->characterRepository->findAll();
    }

    public function delete(Character $character): void
    {
        $this->em->remove($character);
        $this->em->flush();
    }

    public function isEntityFilled(Character $character)
    {
        $errors = $this->validator->validate($character);
        if (count($errors) > 0) {
            $errorMsg = (string) $errors . 'Wrong data for Entity -> ';
            $errorMsg .= json_encode($this->serializeJson($character));
            throw new UnprocessableEntityHttpException($errorMsg);
        }
    }

    public function submit(Character $character, $formName, $data)
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true);

        if (null !== $data && !is_array($dataArray)) {
            throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);
        }

        $form = $this->formFactory->create($formName, $character, ['csrf_protection' => false]);
        $form->submit($dataArray, false);

        $errors = $form->getErrors();
        foreach ($errors as $error) {
            $errorMsg  = 'Error ' . get_class($error->getCause());
            $errorMsg .= ' --> ' . $error->getMessageTemplate();
            $errorMsg .= ' ' . json_encode($error->getMessageParameters());
            throw new LogicException($errorMsg);
        }
    }
}