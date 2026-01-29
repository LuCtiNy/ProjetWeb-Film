<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?int $role = null;

    /**
     * @var Collection<int, Film>
     */
    #[ORM\ManyToMany(targetEntity: Film::class, inversedBy: 'utilisateursFavoris')]
    #[ORM\JoinTable(name: 'favoris')]
    private Collection $favoris;

    /**
     * @var Collection<int, Panier>
     */
    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Panier::class, orphanRemoval: true)]
    private Collection $panier;

    public function __construct()
    {
        $this->favoris = new ArrayCollection();
        $this->panier = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(int $role): static
    {
        $this->role = $role;

        return $this;
    }

    // ========== Méthodes requises par UserInterface ==========

    /**
     * Retourne l'identifiant unique de l'utilisateur (email dans notre cas)
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Retourne les rôles de l'utilisateur
     */
    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];

        // Si role = 2, c'est un admin
        if ($this->role === 2) {
            $roles[] = 'ROLE_ADMIN';
        }

        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {

    }

    /**
     * @return Collection<int, Film>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Film $favori): static
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
        }

        return $this;
    }

    public function removeFavori(Film $favori): static
    {
        $this->favoris->removeElement($favori);

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPanier(): Collection
    {
        return $this->panier;
    }

    public function addPanier(Panier $panier): static
    {
        if (!$this->panier->contains($panier)) {
            $this->panier->add($panier);
            $panier->setUtilisateur($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): static
    {
        if ($this->panier->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getUtilisateur() === $this) {
                $panier->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return int[]
     */
    public function getPanierFilmIds(): array
    {
        return $this->panier->map(fn(Panier $p) => $p->getFilm()->getId())->toArray();
    }
}
