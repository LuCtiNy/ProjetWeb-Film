<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Panier;
use App\Entity\PanierFilm;
use App\Repository\FilmRepository;
use App\Repository\PanierFilmRepository;
use App\Repository\PanierRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier_index')]
    public function index(PanierRepository $panierRepository, PromotionRepository $promotionRepository): Response
    {
        $user = $this->getUser();
        $panier = $panierRepository->findOneBy(['utilisateur' => $user, 'statut' => 'actif']);
        $items = $panier ? $panier->getPanierFilms() : [];

        $jourActuel = (int) date('N');
        $promotion = $promotionRepository->find($jourActuel);
        $tauxPromo = $promotion ? $promotion->getTauxpromo() : 0;

        $total = 0;
        foreach ($items as $item) {
            $prixBase = $item->getFilm()->getPrixLocation();
            $prixFinal = $prixBase * (1 - $tauxPromo / 100);
            $total += ($prixFinal * $item->getQuantite());
        }

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
            'items' => $items,
            'total' => $total,
            'tauxPromo' => $tauxPromo
        ]);
    }

    #[Route('/panier/add/{id}', name: 'app_panier_add')]
    public function add(Film $film, EntityManagerInterface $em, PanierRepository $panierRepository, PanierFilmRepository $pfRepo, Request $request): Response
    {
        $user = $this->getUser();

        $panier = $panierRepository->findOneBy(['utilisateur' => $user, 'statut' => 'actif']);
        if (!$panier) {
            $panier = new Panier();
            $panier->setUtilisateur($user);
            $panier->setStatut('actif');
            $em->persist($panier);
        }

        $existing = $pfRepo->findOneBy([
            'panier' => $panier,
            'film' => $film
        ]);

        if ($existing) {
            $em->remove($existing);
            $em->flush();
            $removed = true;
            $success = false;
        } else {
            $pf = new PanierFilm();
            $pf->setPanier($panier);
            $pf->setFilm($film);
            $pf->setQuantite(1);

            $em->persist($pf);
            $em->flush();
            $success = true;
            $removed = false;
        }

        if ($request->isXmlHttpRequest() || $request->headers->get('X-Requested-With') === 'XMLHttpRequest') {
            return $this->json([
                'success' => $success,
                'removed' => $removed,
                'message' => $success ? 'Film ajouté au panier !' : 'Film retiré du panier.'
            ]);
        }

        return $this->redirectToRoute('app_catalogue');
    }

    #[Route('/panier/remove/{id}', name: 'app_panier_remove')]
    public function remove(PanierFilm $panierFilm, EntityManagerInterface $em): Response
    {
        if ($panierFilm->getPanier()->getUtilisateur() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Cet article ne vous appartient pas.");
        }

        $em->remove($panierFilm);
        $em->flush();

        return $this->redirectToRoute('app_panier_index');
    }
}
