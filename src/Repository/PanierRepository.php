<?php

namespace App\Repository;

use App\Entity\Panier;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Panier>
 */
class PanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panier::class);
    }

    /**
     * @return Panier[]
     */
    public function findByUser(Utilisateur $user): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.utilisateur = :user')
            ->setParameter('user', $user)
            ->orderBy('p.dateAjout', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countByUser(Utilisateur $user): int
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->andWhere('p.utilisateur = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
