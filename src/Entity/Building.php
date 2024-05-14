<?php

namespace App\Entity;

use App\Repository\BuildingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BuildingRepository::class)]
#[ORM\Table(name: '`building`')]
class Building
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, name:'gls_name')]
    #[Assert\NotNull] 
    #[Assert\NotBlank] 
    #[Assert\Length(
        min: 3,
        max: 20, 
    )]
    private ?string $name = null;

    #[ORM\Column(length: 20, name:'gls_slug')]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 20,
    )]
    private ?string $slug = null;

    #[ORM\Column(length: 40, nullable: true, name:'gls_caste')]
    private ?string $caste = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true, name:'gls_strength')]
    #[Assert\PositiveOrZero]
    private ?int $strength = null;

    #[ORM\Column(length: 100, nullable: true, name:'gls_image')]
    #[Assert\Length(
        min: 5,
        max: 50,
    )]
    private ?string $image = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true, name:'gls_note')]
    private ?int $note = null;

    #[ORM\Column(length: 40, name:'gls_identifier')]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 40,
        max: 40, 
    )]
    private ?string $identifier = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, name:'gls_created_at')]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, name:'gls_updated_at')]
    private ?\DateTimeInterface $updated_at = null;

    /**
     * @var Collection<int, Character>s
     */
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'building')]
    private Collection $characters;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
    }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

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

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

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



    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, Character>
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): static
    {
        if (!$this->characters->contains($character)) {
            $this->characters->add($character);
            $character->setBuilding($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): static
    {
        if ($this->characters->removeElement($character)) {
            // set the owning side to null (unless already changed)
            if ($character->getBuilding() === $this) {
                $character->setBuilding(null);
            }
        }

        return $this;
    }
}