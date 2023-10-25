<?php

namespace App\Entity;

use App\Repository\SchedulesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SchedulesRepository::class)]
class Schedules
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $day = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $morning_schedule = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $afternoon_schedule = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): static
    {
        $this->day = $day;

        return $this;
    }

    public function getMorningSchedule(): ?string
    {
        return $this->morning_schedule;
    }

    public function setMorningSchedule(?string $morning_schedule): static
    {
        $this->morning_schedule = $morning_schedule;

        return $this;
    }

    public function getAfternoonSchedule(): ?string
    {
        return $this->afternoon_schedule;
    }

    public function setAfternoonSchedule(?string $afternoon_schedule): static
    {
        $this->afternoon_schedule = $afternoon_schedule;

        return $this;
    }
}
