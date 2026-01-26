<?php

namespace App\Controller;

use App\Entity\Film;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailsFilmController extends AbstractController
{
    #[Route('/detailsFilm/{id}', name: 'detailsFilm')]
    public function index(Film $film, PromotionRepository $promotionRepository): Response
    {
        $jourActuel = (int) date('N');
        $promotion = $promotionRepository->find($jourActuel);
        $tauxPromo = $promotion ? $promotion->getTauxpromo() : 0;

        return $this->render('detailsFilm/index.html.twig', [
            'film' => $film,
            'tauxPromo' => $tauxPromo,
        ]);
    }
}
