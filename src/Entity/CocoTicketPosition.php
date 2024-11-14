<?php

namespace App\Entity;

use App\Repository\CocoTicketPositionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CocoTicketPositionRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_TICKETITEM', fields: ['ticket', 'item'])]
class CocoTicketPosition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cocoTicketPositions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CocoTicket $ticket = null;

    #[ORM\ManyToOne(inversedBy: 'cocoTicketPositions')]
    private ?CocoItem $item = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $explanation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicket(): ?CocoTicket
    {
        return $this->ticket;
    }

    public function setTicket(?CocoTicket $ticket): static
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function getItem(): ?CocoItem
    {
        return $this->item;
    }

    public function setItem(?CocoItem $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getExplanation(): ?string
    {
        return $this->explanation;
    }

    public function setExplanation(?string $explanation): static
    {
        $this->explanation = $explanation;

        return $this;
    }
}
