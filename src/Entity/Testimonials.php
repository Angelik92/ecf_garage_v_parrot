<?php

namespace App\Entity;

use App\Repository\TestimonialsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TestimonialsRepository::class)]
class Testimonials
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Veuillez remplir le nom du client')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le nom du client doit contenir au minimum {{ limit }} caractères. ',
        maxMessage: 'Le nom du client doit contenir au maximum {{ limit }} caractères. '
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z\d\s-]+$/',
        message: 'Le nom du client doit contenir uniquement des lettres et chiffres.'
    )]
    private ?string $client = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez saisir la date.')]
    #[Assert\Range(
        min: 'today -1 year',
        max: 'now',
        notInRangeMessage: 'La date du service doit dater de moins d\'un an'
    )]
    private ?\DateTime $date_of_service = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez saisir la note.')]
    #[Assert\Range(
        min: 0,
        max: 5,
        notInRangeMessage: 'La note doit être comprise entre {{ min }} et {{ max }}. ')]
    private ?int $rating = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Veuillez remplir le commentaire')]
    #[Assert\Length(
        min: 3,
        max: 400,
        minMessage: 'Le commentaire doit contenir au minimum {{ limit }} caractères. ',
        maxMessage: 'Le commentaire doit contenir au maximum {{ limit }} caractères. '
    )]
    private ?string $content = null;

    #[ORM\Column(nullable: true)]
    private ?bool $approved = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $update_at = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Services $service = null;

    #[ORM\ManyToOne(inversedBy: 'testimonials')]
    private ?User $moderator = null;

    #[ORM\Column(nullable: false)]
    private ?bool $create_by_phone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(string $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getDateOfService(): ?\DateTime
    {
        return $this->date_of_service;
    }

    public function setDateOfService(\DateTime $date_of_service): static
    {
        $this->date_of_service = $date_of_service;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function isApproved(): ?bool
    {
        return $this->approved;
    }

    public function setApproved(?bool $approved): static
    {
        $this->approved = $approved;

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

    public function getService(): ?Services
    {
        return $this->service;
    }

    public function setService(?Services $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getModerator(): ?User
    {
        return $this->moderator;
    }

    public function setModerator(?User $moderator): static
    {
        $this->moderator = $moderator;

        return $this;
    }

    public function isCreateByPhone(): ?bool
    {
        return $this->create_by_phone;
    }

    public function setCreateByPhone(bool $create_by_phone): static
    {
        $this->create_by_phone = $create_by_phone;

        return $this;
    }
}
