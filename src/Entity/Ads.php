<?php

namespace App\Entity;

use App\Repository\AdsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity('registration_nb', message: 'L\'immatriculation est déjà enregistré.')]
#[ORM\Entity(repositoryClass: AdsRepository::class)]
class Ads
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Veuillez remplir le champ du titre.')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le titre doit contenir au minimum {{ limit }} caractères. ',
        maxMessage: 'Le titre doit contenir au maximum {{ limit }} caractères. '
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z\d\s-]+$/',
        message: 'Le titre doit contenir uniquement des lettres et chiffres.'
    )]
    private ?string $title = null;

    #[ORM\Column(length: 20, unique: 'true')]
    #[Assert\NotBlank(message: 'Veuillez remplir le champ de l\'immatriculation.')]
    #[Assert\Length(
        min: 5,
        max: 20,
        minMessage: 'L\'immatriculation doit contenir au minimum {{ limit }} caractères. ',
        maxMessage: 'L\'immatriculation doit contenir au maximum {{ limit }} caractères. '
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z\d\s-]+$/',
        message: 'Le titre doit contenir uniquement des lettres et chiffres.'
    )]
    private ?string $registration_nb = null;

    #[ORM\ManyToOne(inversedBy: 'ads')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cars $car = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez remplir le champ de l\'année de construction.')]
    #[Assert\Range(
        min: 1960,
        max: 2023,
        notInRangeMessage: 'L\'année doit être comprise entre {{ min }} et {{ max }}. ')]
    private ?int $built = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez remplir le champ de le nombre de kilomètre.')]
    #[Assert\Positive(message: 'La valeur doit être positive')]
    private ?int $kilometers = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez remplir le champ de le prix.')]
    #[Assert\Positive(message: 'La valeur doit être positive')]
    private ?int $price = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez remplir le champ de la date de création.')]
    private \DateTime $create_at;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $update_at = null;

    #[ORM\OneToMany(mappedBy: 'ads', targetEntity: Pictures::class, cascade: ['persist', 'remove'])]
    private Collection $pictures;

    #[ORM\ManyToOne(inversedBy: 'ads')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez remplir le champ {{ value }}')]
    #[Assert\Length(
        min: 10,
        max: 500,
        minMessage: 'La description doit contenir au minimum {{ limit }} caractères. ',
        maxMessage: 'La description doit contenir au maximum {{ limit }} caractères. '
    )]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'adsUpdate')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $updateBy = null;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getRegistrationNb(): ?string
    {
        return $this->registration_nb;
    }

    public function setRegistrationNb(string $registration_nb): static
    {
        $this->registration_nb = $registration_nb;

        return $this;
    }

    public function getCar(): ?Cars
    {
        return $this->car;
    }

    public function setCar(?Cars $car): static
    {
        $this->car = $car;

        return $this;
    }

    public function getBuilt(): ?int
    {
        return $this->built;
    }

    public function setBuilt(int $built): static
    {
        $this->built = $built;

        return $this;
    }

    public function getKilometers(): ?int
    {
        return $this->kilometers;
    }

    public function setKilometers(int $kilometers): static
    {
        $this->kilometers = $kilometers;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCreateAt(): ?\DateTime
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTime $create_at): static
    {
        $this->create_at = $create_at;

        return $this;
    }

    public function getUpdateAt(): ?\DateTime
    {
        return $this->update_at;
    }

    public function setUpdateAt(?\DateTime $update_at): static
    {
        $this->update_at = $update_at;

        return $this;
    }

    /**
     * @return Collection<int, Pictures>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Pictures $pictures): static
    {
        if (!$this->pictures->contains($pictures)) {
            $this->pictures->add($pictures);
            $pictures->setAds($this);
        }

        return $this;
    }

    public function removePicture(Pictures $pictures): static
    {
        if ($this->pictures->removeElement($pictures)) {
            // set the owning side to null (unless already changed)
            if ($pictures->getAds() === $this) {
                $pictures->setAds(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUpdateBy(): ?User
    {
        return $this->updateBy;
    }

    public function setUpdateBy(?User $updateBy): static
    {
        $this->updateBy = $updateBy;

        return $this;
    }
}
