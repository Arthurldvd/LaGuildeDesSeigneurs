<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\Table(name: '`character`')]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = 1;

    #[ORM\Column(length: 20, name:'gls_name')]
    #[Assert\NotNull] // Pour que ce ne soit pas null
    #[Assert\NotBlank] // Pour que ce ne soit pas blanc
    #[Assert\Length( //Définit une taille mini et maxi
        min: 3,
        max: 20, // Messages pour customisation, sinon on peut les supprimer
    )]
    private ?string $name;

    #[ORM\Column(length: 50)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 50, // 50 caractères
    )]
    private ?string $surname;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $caste = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Length(
        min: 3,
        max: 20,
    )]
    private ?string $knowledge = null ;

    #[ORM\Column(type: Types::SMALLINT, nullable: true, name:'gls_intelligence')]
    #[Assert\PositiveOrZero] // OU #[Assert\Positive] si on ne veut pas de 0
    private ?int $intelligence = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Assert\PositiveOrZero]
    private ?int $strength = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length(
        min: 5,
        max: 50,
    )]
    private ?string $image = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 20,
    )]
    private ?string $slug;

    #[ORM\Column(length: 20)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 20,
    )]
    private ?string $kind;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name:'gls_creation')]
    private ?\DateTimeInterface $creation = null;

    #[ORM\Column(length: 40)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 40, // si on veut une taille fixe il suffit
        max: 40, // de mettre la même valeur pour min et max
    )]
    private ?string $identifier = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modification = null;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    private ?Building $building = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getCaste(): ?string
    {
        return $this->caste;
    }

    public function setCaste(?string $caste): self
    {
        $this->caste = $caste;

        return $this;
    }

    public function getKnowledge(): ?string
    {
        return $this->knowledge;
    }

    public function setKnowledge(?string $knowledge): self
    {
        $this->knowledge = $knowledge;

        return $this;
    }

    public function getIntelligence(): ?int
    {
        return $this->intelligence;
    }

    public function setIntelligence(?int $intelligence): self
    {
        $this->intelligence = $intelligence;

        return $this;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(?int $strength): self
    {
        $this->strength = $strength;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getKind(): ?string
    {
        return $this->kind;
    }

    public function setKind(string $kind): static
    {
        $this->kind = $kind;

        return $this;
    }

    public function getCreation(): ?\DateTimeInterface
    {
        return $this->creation;
    }

    public function setCreation(\DateTimeInterface $creation): static
    {
        $this->creation = $creation;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): static
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getModification(): ?\DateTimeInterface
    {
        return $this->modification;
    }

    public function setModification(?\DateTimeInterface $modification): static
    {
        $this->modification = $modification;

        return $this;
    }

    public function getBuilding(): ?Building
    {
        return $this->building;
    }

    public function setBuilding(?Building $building): static
    {
        $this->building = $building;

        return $this;
    }

}
