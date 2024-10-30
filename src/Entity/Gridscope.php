<?php

namespace App\Entity;

use App\Repository\GridscopeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: GridscopeRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_NAMEKEY', fields: ['name', 'scopeKey'])]
#[UniqueEntity(fields: ['name'], message: 'flash.fail.taken')]
#[UniqueEntity(fields: ['scopeKey'], message: 'flash.fail.taken')]
class Gridscope
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 100)]
    private ?string $scopeKey = null;

    /**
     * @var Collection<int, Gridpool>
     */
    #[ORM\OneToMany(targetEntity: Gridpool::class, mappedBy: 'scope')]
    private Collection $gridpools;

    public function __construct()
    {
        $this->gridpools = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getScopeKey(): ?string
    {
        return $this->scopeKey;
    }

    public function setScopeKey(string $scopeKey): static
    {
        $this->scopeKey = $scopeKey;

        return $this;
    }

    public function getChecksum(): string
    {
        $allValues = [
            $this->getName(),
            $this->getScopeKey(),
            $this->getDescription(),
        ];
        $serializedValues = serialize($allValues);

        return md5($serializedValues);
    }

    /**
     * @return Collection<int, Gridpool>
     */
    public function getGridpools(): Collection
    {
        return $this->gridpools;
    }

    public function addGridpool(Gridpool $gridpool): static
    {
        if (!$this->gridpools->contains($gridpool)) {
            $this->gridpools->add($gridpool);
            $gridpool->setScope($this);
        }

        return $this;
    }

    public function removeGridpool(Gridpool $gridpool): static
    {
        if ($this->gridpools->removeElement($gridpool)) {
            // set the owning side to null (unless already changed)
            if ($gridpool->getScope() === $this) {
                $gridpool->setScope(null);
            }
        }

        return $this;
    }
}
