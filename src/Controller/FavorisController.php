<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class FavorisController extends AbstractController
{
    #[Route('/favoris', name: 'app_favoris_index')]
    public function index(PromotionRepository $promotionRepository): Response
    {
        $user = $this->getUser();
        
        $jourActuel = (int) date('N');
        $promotion = $promotionRepository->find($jourActuel);
        $tauxPromo = $promotion ? $promotion->getTauxpromo() : 0;

        return $this->render('favoris/index.html.twig', [
            'films' => $user->getFavoris(),
            'tauxPromo' => $tauxPromo,
        ]);
    }

    #[Route('/favoris/add/{id}', name: 'app_like_add')]
    public function add(Film $film, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        
        $isAdded = false;
        if ($user->getFavoris()->contains($film)) {
            $user->removeFavori($film);
        } else {
            $user->addFavori($film);
            $isAdded = true;
        }

        $entityManager->flush();

        if ($request->isXmlHttpRequest() || $request->headers->get('X-Requested-With') === 'XMLHttpRequest') {
            return $this->json([
                'added' => $isAdded,
                'message' => $isAdded ? 'Film ajouté aux favoris.' : 'Film retiré des favoris.'
            ]);
        }

        $referer = $request->headers->get('referer');
        return $referer ? $this->redirect($referer) : $this->redirectToRoute('app_accueil');
    }
}
