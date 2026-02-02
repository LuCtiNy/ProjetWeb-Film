<?php

namespace App\Entity;

use App\Repository\JoursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JoursRepository::class)]
#[ORM\Table(name: 'jours')]
class Jours
{
    #[ORM\Id]
    #[ORM\Column(name: 'idjour', type: 'smallint')]
    private ?int $idjour = null;

    #[ORM\Column(name: 'nomjour', type: 'string', length: 10)]
    private ?string $nomjour = null;

    public function getIdjour(): ?int
    {
        return $this->idjour;
    }

    public function setIdjour(int $idjour): static
    {
        $this->idjour = $idjour;

        return $this;
    }

    public function getNomjour(): ?string
    {
        return $this->nomjour;
    }

    public function setNomjour(string $nomjour): static
    {
        $this->nomjour = $nomjour;

        return $this;
    }
}
