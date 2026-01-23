<?php

namespace App\Controller;

use App\Entity\Film;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailsFilmController extends AbstractController
{
    #[Route('/detailsFilm/{id}', name: 'detailsFilm')]
    public function index(Film $film): Response
    {
        return $this->render('detailsFilm/index.html.twig', [
            'film' => $film,
        ]);
    }
}
