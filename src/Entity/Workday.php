<?php

namespace App\Entity;

use App\Repository\WorkdayRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkdayRepository::class)]
class Workday
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $startHour = null;

    #[ORM\Column]
    private ?bool $isAway = null;

    #[ORM\Column]
    private ?bool $isHomeoffice = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $day = null;

    #[ORM\ManyToOne(inversedBy: 'workdays')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user = null;

    #[ORM\Column(nullable: true)]
    private ?int $workHours = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartHour(): ?int
    {
        return $this->startHour;
    }

    public function setStartHour(?int $startHour): static
    {
        $this->startHour = $startHour;

        return $this;
    }

    public function isAway(): ?bool
    {
        return $this->isAway;
    }

    public function setAway(bool $isAway): static
    {
        $this->isAway = $isAway;

        return $this;
    }

    public function isHomeoffice(): ?bool
    {
        return $this->isHomeoffice;
    }

    public function setHomeoffice(bool $isHomeoffice): static
    {
        $this->isHomeoffice = $isHomeoffice;

        return $this;
    }

    public function getDay(): ?\DateTimeInterface
    {
        return $this->day;
    }

    public function setDay(\DateTimeInterface $day): static
    {
        $this->day = $day;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getWorkHours(): ?int
    {
        return $this->workHours;
    }

    public function setWorkHours(?int $workHours): static
    {
        $this->workHours = $workHours;

        return $this;
    }
}
