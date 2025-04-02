<?php

namespace App\Entity;

use App\Repository\BoosterCountryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'l3_booster_country')]
#[ORM\Entity(repositoryClass: BoosterCountryRepository::class)]
class BoosterCountry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'boosterCountries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Booster $booster = null;

    #[ORM\ManyToOne(inversedBy: 'countryBoosters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Country $country = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooster(): ?Booster
    {
        return $this->booster;
    }

    public function setBooster(?Booster $booster): static
    {
        $this->booster = $booster;

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
