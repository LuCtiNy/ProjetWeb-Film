<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(FilmRepository $filmRepository, PromotionRepository $promotionRepository): Response
    {
        $films = $filmRepository->findBy([], ['id' => 'DESC'], 8);
        
        $jourActuel = (int) date('N');
        $promotion = $promotionRepository->find($jourActuel);
        $tauxPromo = $promotion ? $promotion->getTauxpromo() : 0;
        
        return $this->render('accueil/index.html.twig', [
            'films' => $films,
            'tauxPromo' => $tauxPromo,
        ]);
    }

}
