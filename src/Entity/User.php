<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;



#[UniqueEntity('email', message: 'L\'email est déjà enregistré.')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un mail')]
    #[Assert\Length(
        min: 10,
        max: 180,
        minMessage: 'L\'email doit contenir au moins {{ limit }} lettres',
        maxMessage: 'L\'email doit contenir au maximum {{limit}} lettres.')]
    #[Assert\Email(message: '{{ value }} n\'est pas une adresse valide ! ')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message:'Veuillez renseigner un mot de passe')]
    #[Assert\Length(min:8, minMessage:'Le mot de passe doit faire au minimum {{ limit }} caractères')]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Ads::class)]
    private Collection $ads;

    #[ORM\OneToMany(mappedBy: 'moderator', targetEntity: Testimonials::class)]
    private Collection $testimonials;

    #[ORM\Column]
    private ?\DateTime $create_at = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le prénom doit contenir au moins {{limit}} lettres',
        maxMessage: 'Le prénom doit contenir au maximum {{limit}} lettres.')]
    #[Assert\NotBlank(message: 'Veuillez remplir le champ prénom')]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÖØ-öø-ÿ\s-]+$/u',
        message: 'Le prénom ne peut contenir que des lettres.')]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    private ?string $lastname = null;

    #[ORM\OneToMany(mappedBy: 'updateBy', targetEntity: Ads::class)]
    private Collection $adsUpdate;

    public function __construct()
    {
        $this->ads = new ArrayCollection();
        $this->testimonials = new ArrayCollection();
        $this->adsUpdate = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $ad->setAuthor($this);
        }

        return $this;
    }

    public function removeAd(Ads $ad): static
    {
        if ($this->ads->removeElement($ad)) {
            // set the owning side to null (unless already changed)
            if ($ad->getAuthor() === $this) {
                $ad->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Testimonials>
     */
    public function getTestimonials(): Collection
    {
        return $this->testimonials;
    }

    public function addTestimonial(Testimonials $testimonial): static
    {
        if (!$this->testimonials->contains($testimonial)) {
            $this->testimonials->add($testimonial);
            $testimonial->setModerator($this);
        }

        return $this;
    }

    public function removeTestimonial(Testimonials $testimonial): static
    {
        if ($this->testimonials->removeElement($testimonial)) {
            // set the owning side to null (unless already changed)
            if ($testimonial->getModerator() === $this) {
                $testimonial->setModerator(null);
            }
        }

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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return Collection<int, Ads>
     */
    public function getAdsUpdate(): Collection
    {
        return $this->adsUpdate;
    }

    public function addAdsUpdate(Ads $adsUpdate): static
    {
        if (!$this->adsUpdate->contains($adsUpdate)) {
            $this->adsUpdate->add($adsUpdate);
            $adsUpdate->setUpdateBy($this);
        }

        return $this;
    }

    public function removeAdsUpdate(Ads $adsUpdate): static
    {
        if ($this->adsUpdate->removeElement($adsUpdate)) {
            // set the owning side to null (unless already changed)
            if ($adsUpdate->getUpdateBy() === $this) {
                $adsUpdate->setUpdateBy(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }
}
