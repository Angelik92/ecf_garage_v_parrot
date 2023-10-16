<?php

namespace App\Entity;

use App\Repository\GaragesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GaragesRepository::class)]
class Garages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez remplir le champ du nom.')]
    #[Assert\Length(
        min: 10,
        max: 255,
        minMessage: 'Le nom doit contenir au moins {{ limit }} lettres',
        maxMessage: 'Le nom doit contenir au maximum {{ limit }} lettres.')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez remplir le champ de l\'adresse.')]
    #[Assert\Length(
        min: 10,
        max: 255,
        minMessage: 'L\'adresse doit contenir au moins {{ limit }} lettres',
        maxMessage: 'L\'adresse doit contenir au maximum {{ limit }} lettres.')]
    private ?string $address = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez remplir le champ du code postale.')]
    private ?int $zip_code = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez remplir le champ de la ville.')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'La ville doit contenir au moins {{ limit }} lettres',
        maxMessage: 'La ville doit contenir au maximum {{ limit }} lettres.')]
    private ?string $city = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: 'Veuillez remplir le champ du téléphone.')]
    private ?string $phone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zip_code;
    }

    public function setZipCode(int $zip_code): static
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
}
