<?php

namespace App\Entity;

use App\Repository\CarsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity('model', message:'Le modèle est déjà enregistré')]
#[ORM\Entity(repositoryClass: CarsRepository::class)]
class Cars
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100 , unique: true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner le modèle')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le modèle doit contenir au moins {{ limit }} lettres',
        maxMessage: 'Le modèle doit contenir au maximum {{ limit }} lettres.')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z\d\s.-]+$/',
        message: 'Le modèle ne peut contenir que des lettres et des chiffres et \'-\'.')]
    private ?string $model = null;

    #[ORM\Column]
    #[Assert\Range(
        min: 50,
        max: 300,
        notInRangeMessage: 'La puissance doit être comprise entre {{ min }} et {{ max }} CH ! '
    )]
    private ?int $power = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Veuillez choisir le type de boite de vitesse')]
    private ?Gearboxes $Gearbox = null;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Veuillez choisir ou créer une marque')]
    private ?Brands $brand = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Veuillez choisir le type de carburant')]
    private ?Fuels $fuel = null;

    #[ORM\OneToMany(mappedBy: 'car', targetEntity: Ads::class)]
    private Collection $ads;

    public function __construct()
    {
        $this->ads = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getPower(): ?int
    {
        return $this->power;
    }

    public function setPower(?int $power): static
    {
        $this->power = $power;

        return $this;
    }

    public function getGearbox(): ?Gearboxes
    {
        return $this->Gearbox;
    }

    public function setGearbox(?Gearboxes $Gearbox): static
    {
        $this->Gearbox = $Gearbox;

        return $this;
    }

    public function getBrand(): ?Brands
    {
        return $this->brand;
    }

    public function setBrand(?Brands $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getFuel(): ?Fuels
    {
        return $this->fuel;
    }

    public function setFuel(?Fuels $fuel): static
    {
        $this->fuel = $fuel;

        return $this;
    }

    /**
     * @return Collection<int, Ads>
     */
    public function getAds(): Collection
    {
        return $this->ads;
    }

    public function addAd(Ads $ad): static
    {
        if (!$this->ads->contains($ad)) {
            $this->ads->add($ad);
            $ad->setCar($this);
        }

        return $this;
    }

    public function removeAd(Ads $ad): static
    {
        if ($this->ads->removeElement($ad)) {
            // set the owning side to null (unless already changed)
            if ($ad->getCar() === $this) {
                $ad->setCar(null);
            }
        }

        return $this;
    }
}
