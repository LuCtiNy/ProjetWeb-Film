<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'Bienvenue sur le site',
        ]);
    }

    #[Route('/catalogue', name: 'app_catalogue')]
    public function catalogue(FilmRepository $filmRepository): Response
    {
        return $this->render('catalogue/index.html.twig', [
            'films' => $filmRepository->findAll(),
        ]);
    }

}