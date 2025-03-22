<?php

namespace App\Entity;

use App\Repository\ExpansionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'l3_expansion')]
#[ORM\Entity(repositoryClass: ExpansionRepository::class)]
class Expansion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    private ?string $number = null;

    /**
     * @var Collection<int, Booster>
     */
    #[ORM\OneToMany(targetEntity: Booster::class, mappedBy: 'expansion', orphanRemoval: true)]
    private Collection $boosters;

    public function __construct()
    {
        $this->boosters = new ArrayCollection();
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

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection<int, Booster>
     */
    public function getBoosters(): Collection
    {
        return $this->boosters;
    }

    public function addBooster(Booster $booster): static
    {
        if (!$this->boosters->contains($booster)) {
            $this->boosters->add($booster);
            $booster->setExpansion($this);
        }

        return $this;
    }

    public function removeBooster(Booster $booster): static
    {
        if ($this->boosters->removeElement($booster)) {
            // set the owning side to null (unless already changed)
            if ($booster->getExpansion() === $this) {
                $booster->setExpansion(null);
            }
        }

        return $this;
    }
}
