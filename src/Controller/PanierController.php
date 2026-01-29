<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Panier;
use App\Repository\FilmRepository;
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
        $items = $panierRepository->findByUser($user);

        // Récupération de la promo du jour
        $jourActuel = (int) date('N');
        $promotion = $promotionRepository->find($jourActuel);
        $tauxPromo = $promotion ? $promotion->getTauxpromo() : 0;

        $total = 0;
        foreach ($items as $item) {
            $prixBase = $item->getFilm()->getPrixLocation();
            $prixFinal = $prixBase * (1 - $tauxPromo / 100);
            $total += $prixFinal;
        }

        return $this->render('panier/index.html.twig', [
            'items' => $items,
            'total' => $total,
            'tauxPromo' => $tauxPromo
        ]);
    }

    #[Route('/panier/add/{id}', name: 'app_panier_add')]
    public function add(Film $film, EntityManagerInterface $em, PanierRepository $panierRepository, Request $request): Response
    {
        $user = $this->getUser();

        // Vérifier si le film est déjà dans le panier
        $existing = $panierRepository->findOneBy([
            'utilisateur' => $user,
            'film' => $film
        ]);

        $success = false;
        if (!$existing) {
            $panier = new Panier();
            $panier->setUtilisateur($user);
            $panier->setFilm($film);

            $em->persist($panier);
            $em->flush();
            $success = true;
        }

        if ($request->isXmlHttpRequest() || $request->headers->get('X-Requested-With') === 'XMLHttpRequest') {
            return $this->json([
                'success' => $success,
                'alreadyIn' => !$success,
                'message' => $success ? 'Film ajouté au panier !' : 'Ce film est déjà dans votre panier.'
            ]);
        }

        return $this->redirectToRoute('app_catalogue');
    }

    #[Route('/panier/remove/{id}', name: 'app_panier_remove')]
    public function remove(Panier $panierItem, EntityManagerInterface $em): Response
    {
        // Vérifier que l'item appartient bien à l'utilisateur
        if ($panierItem->getUtilisateur() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Ce panier ne vous appartient pas.");
        }

        $em->remove($panierItem);
        $em->flush();

        return $this->redirectToRoute('app_panier_index');
    }
}
