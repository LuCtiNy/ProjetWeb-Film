<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    public function searchFilms(?string $search, ?string $genre, ?string $annee, ?string $sort = 'id_asc')
    {
        $qb = $this->createQueryBuilder('f');

        if ($search) {
            $qb->andWhere('LOWER(f.titre) LIKE LOWER(:search)')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($genre) {
            $qb->andWhere('LOWER(f.genres) LIKE LOWER(:genre)')
               ->setParameter('genre', '%' . $genre . '%');
        }

        if ($annee) {
            $qb->andWhere('f.annee = :annee')
               ->setParameter('annee', $annee);
        }

        switch ($sort) {
            case 'prix_asc':
                $qb->orderBy('f.prixLocation', 'ASC');
                break;
            case 'prix_desc':
                $qb->orderBy('f.prixLocation', 'DESC');
                break;
            case 'date_desc':
                $qb->orderBy('f.annee', 'DESC');
                break;
            case 'alpha_asc':
                $qb->orderBy('f.titre', 'ASC');
                break;
            default:
                $qb->orderBy('f.id', 'ASC');
                break;
        }

        return $qb->getQuery()->getResult();
    }
}
