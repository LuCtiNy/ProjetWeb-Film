<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use App\Repository\PromotionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CatalogueController extends AbstractController
{
    #[Route('/catalogue', name: 'app_catalogue')]
    public function index(Request $request, FilmRepository $filmRepository, PromotionRepository $promotionRepository, PaginatorInterface $paginator): Response
    {
        $search = $request->query->get('search');
        $genre = $request->query->get('genre');
        $annee = $request->query->get('annee');
        $tri = $request->query->get('tri', 'id_asc');

        // On récupère d'abord TOUS les films pour alimenter les listes déroulantes (genres et années)
        $allFilms = $filmRepository->findAll();
        
        // On récupère la REQUETE (et non les résultats) pour la pagination
        $query = $filmRepository->searchFilmsQuery($search, $genre, $annee, $tri);

        // On pagine les résultats
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20 // Limite par page
        );

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
            'films' => $pagination,
            'genres' => $genres,
            'annees' => $annees,
            'tauxPromo' => $tauxPromo,
            'currentSearch' => $search,
            'currentGenre' => $genre,
            'currentAnnee' => $annee,
            'currentSort' => $tri,
        ]);
    }
}
