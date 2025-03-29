<?php

namespace App\Entity;

use App\Repository\BoosterRepository;
use Doctrine\ORM\Mapping as ORM;

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

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Country $country = null;

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

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }
}
