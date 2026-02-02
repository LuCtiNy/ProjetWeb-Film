<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PromotionRepository::class)]
#[ORM\Table(name: 'promotion')]
class Promotion
{
    #[ORM\Id]
    #[ORM\Column(name: 'idjour', type: 'smallint')]
    private ?int $idjour = null;

    #[ORM\Column(name: 'tauxpromo', type: 'decimal', precision: 10, scale: 2)]
    private ?string $tauxpromo = null;

    #[ORM\OneToOne(targetEntity: Jours::class)]
    #[ORM\JoinColumn(name: 'idjour', referencedColumnName: 'idjour')]
    private ?Jours $jour = null;

    public function getIdjour(): ?int
    {
        return $this->idjour;
    }

    public function setIdjour(int $idjour): static
    {
        $this->idjour = $idjour;

        return $this;
    }

    public function getTauxpromo(): ?string
    {
        return $this->tauxpromo;
    }

    public function setTauxpromo(string $tauxpromo): static
    {
        $this->tauxpromo = $tauxpromo;

        return $this;
    }

    public function getJour(): ?Jours
    {
        return $this->jour;
    }

    public function setJour(?Jours $jour): static
    {
        $this->jour = $jour;

        return $this;
    }
}
