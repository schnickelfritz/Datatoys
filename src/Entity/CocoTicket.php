<?php

namespace App\Entity;

use App\Repository\CocoTicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CocoTicketRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_TICKETNAME', fields: ['name'])]
class CocoTicket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $name = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $category = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dueDate = null;

    #[ORM\Column(nullable: true)]
    private ?int $priority = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $transferStatus = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $processStatus = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $explanation = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageCount = null;

    #[ORM\Column(nullable: true)]
    private ?int $itemCount = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $type = null;

    /**
     * @var Collection<int, CocoTicketPosition>
     */
    #[ORM\OneToMany(targetEntity: CocoTicketPosition::class, mappedBy: 'ticket')]
    private Collection $cocoTicketPositions;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'parentTickets')]
    private ?self $parentTicket = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parentTicket')]
    private Collection $parentTickets;

    public function __construct()
    {
        $this->cocoTicketPositions = new ArrayCollection();
        $this->parentTickets = new ArrayCollection();
    }

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeInterface $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getTransferStatus(): ?string
    {
        return $this->transferStatus;
    }

    public function setTransferStatus(?string $transferStatus): static
    {
        $this->transferStatus = $transferStatus;

        return $this;
    }

    public function getProcessStatus(): ?string
    {
        return $this->processStatus;
    }

    public function setProcessStatus(?string $processStatus): static
    {
        $this->processStatus = $processStatus;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

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

    public function getImageCount(): ?int
    {
        return $this->imageCount;
    }

    public function setImageCount(?int $imageCount): static
    {
        $this->imageCount = $imageCount;

        return $this;
    }

    public function getItemCount(): ?int
    {
        return $this->itemCount;
    }

    public function setItemCount(?int $itemCount): static
    {
        $this->itemCount = $itemCount;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, CocoTicketPosition>
     */
    public function getCocoTicketPositions(): Collection
    {
        return $this->cocoTicketPositions;
    }

    public function addCocoTicketPosition(CocoTicketPosition $cocoTicketPosition): static
    {
        if (!$this->cocoTicketPositions->contains($cocoTicketPosition)) {
            $this->cocoTicketPositions->add($cocoTicketPosition);
            $cocoTicketPosition->setTicket($this);
        }

        return $this;
    }

    public function removeCocoTicketPosition(CocoTicketPosition $cocoTicketPosition): static
    {
        if ($this->cocoTicketPositions->removeElement($cocoTicketPosition)) {
            // set the owning side to null (unless already changed)
            if ($cocoTicketPosition->getTicket() === $this) {
                $cocoTicketPosition->setTicket(null);
            }
        }

        return $this;
    }

    public function getParentTicket(): ?self
    {
        return $this->parentTicket;
    }

    public function setParentTicket(?self $parentTicket): static
    {
        $this->parentTicket = $parentTicket;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getParentTickets(): Collection
    {
        return $this->parentTickets;
    }

    public function addParentTicket(self $parentTicket): static
    {
        if (!$this->parentTickets->contains($parentTicket)) {
            $this->parentTickets->add($parentTicket);
            $parentTicket->setParentTicket($this);
        }

        return $this;
    }

    public function removeParentTicket(self $parentTicket): static
    {
        if ($this->parentTickets->removeElement($parentTicket)) {
            // set the owning side to null (unless already changed)
            if ($parentTicket->getParentTicket() === $this) {
                $parentTicket->setParentTicket(null);
            }
        }

        return $this;
    }

}
