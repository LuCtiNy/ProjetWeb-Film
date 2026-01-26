<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CatalogueController extends AbstractController
{
    #[Route('/catalogue', name: 'app_catalogue')]
    public function index(Request $request, FilmRepository $filmRepository, PromotionRepository $promotionRepository): Response
    {
        $search = $request->query->get('search');
        $genre = $request->query->get('genre');
        $annee = $request->query->get('annee');
        $sort = $request->query->get('sort', 'id_asc');

        $allFilms = $filmRepository->findAll();
        
        $films = $filmRepository->searchFilms($search, $genre, $annee, $sort);

        $jourActuel = (int) date('N');
        $promotion = $promotionRepository->find($jourActuel);
        $tauxPromo = $promotion ? $promotion->getTauxpromo() : 0;

        $genres = [];
        foreach ($allFilms as $f) {
            if ($f->getGenres()) {
                $filmGenres = array_map('trim', explode(',', $f->getGenres()));
                $genres = array_merge($genres, $filmGenres);
            }
        }
        $genres = array_unique($genres);
        sort($genres);

        $annees = [];
        foreach ($allFilms as $f) {
            $annees[] = $f->getAnnee();
        }
        $annees = array_unique($annees);
        sort($annees);

        return $this->render('catalogue/index.html.twig', [
            'films' => $films,
            'genres' => $genres,
            'annees' => $annees,
            'tauxPromo' => $tauxPromo,
            'currentSearch' => $search,
            'currentGenre' => $genre,
            'currentAnnee' => $annee,
            'currentSort' => $sort,
        ]);
    }
}
