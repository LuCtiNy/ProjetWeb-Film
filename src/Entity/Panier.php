<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\PanierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
#[ORM\Table(name: 'panier')]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'panier')]
    #[ORM\JoinColumn(nullable: false, name: 'utilisateur_id')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = "actif";

    #[ORM\Column]
    private ?\DateTimeImmutable $dateCreation = null;

    /**
     * @var Collection<int, PanierFilm>
     */
    #[ORM\OneToMany(mappedBy: 'panier', targetEntity: PanierFilm::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $panierFilms;

    public function __construct()
    {
        $this->dateCreation = new \DateTimeImmutable();
        $this->panierFilms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeImmutable $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * @return Collection<int, PanierFilm>
     */
    public function getPanierFilms(): Collection
    {
        return $this->panierFilms;
    }

    public function addPanierFilm(PanierFilm $panierFilm): static
    {
        if (!$this->panierFilms->contains($panierFilm)) {
            $this->panierFilms->add($panierFilm);
            $panierFilm->setPanier($this);
        }

        return $this;
    }

    public function removePanierFilm(PanierFilm $panierFilm): static
    {
        if ($this->panierFilms->removeElement($panierFilm)) {
            // set the owning side to null (unless already changed)
            if ($panierFilm->getPanier() === $this) {
                $panierFilm->setPanier(null);
            }
        }

        return $this;
    }
}
