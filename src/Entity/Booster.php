<?php

namespace App\Entity;

use App\Repository\BoosterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'l3_booster')]
#[ORM\Entity(repositoryClass: BoosterRepository::class)]
class Booster
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'boosters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Expansion $expansion = null;

    #[Assert\Positive(message: "Le prix doit être positif.")]
    #[ORM\Column]
    private ?float $price = null;

    #[Assert\Positive(message: "La quantité en stock doit être positive.")]
    #[ORM\Column]
    private ?int $stock = null;

    /**
     * @var Collection<int, BoosterCountry>
     */
    #[ORM\OneToMany(targetEntity: BoosterCountry::class, mappedBy: 'booster', orphanRemoval: true)]
    private Collection $boosterCountries;

    public function __construct()
    {
        $this->boosterCountries = new ArrayCollection();
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

    public function getExpansion(): ?Expansion
    {
        return $this->expansion;
    }

    public function getExpansionName(): ?String
    {
        return $this->expansion->getName();
    }

    public function setExpansion(?Expansion $expansion): static
    {
        $this->expansion = $expansion;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Collection<int, BoosterCountry>
     */
    public function getBoosterCountries(): Collection
    {
        return $this->boosterCountries;
    }

    public function addBoosterCountry(BoosterCountry $boosterCountry): static
    {
        if (!$this->boosterCountries->contains($boosterCountry)) {
            $this->boosterCountries->add($boosterCountry);
            $boosterCountry->setBooster($this);
        }

        return $this;
    }

    public function removeBoosterCountry(BoosterCountry $boosterCountry): static
    {
        if ($this->boosterCountries->removeElement($boosterCountry)) {
            // set the owning side to null (unless already changed)
            if ($boosterCountry->getBooster() === $this) {
                $boosterCountry->setBooster(null);
            }
        }

        return $this;
    }
}
