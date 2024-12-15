<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    /**
     * Récupère les séries les plus populaires, triées par une métrique donnée (par exemple, `views` ou `rating`).
     *
     * @param string $metric Le champ utilisé pour trier les séries (par défaut : `rating`).
     * @param int $limit Le nombre maximum de séries à retourner (par défaut : 10).
     * @return Serie[] Retourne un tableau de séries triées par popularité.
     */
    public function findPopular(string $metric = 'rating', int $limit = 10): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.' . $metric, 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche des séries contenant un mot-clé dans leur titre ou description.
     *
     * @param string $keyword Le mot-clé recherché.
     * @return Serie[] Retourne les séries correspondant au mot-clé.
     */
    public function searchByKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.title LIKE :keyword OR s.description LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%')
            ->orderBy('s.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les séries ajoutées récemment.
     *
     * @param int $limit Le nombre maximum de séries à retourner (par défaut : 10).
     * @return Serie[] Retourne les séries ajoutées récemment, triées par date de création.
     */
    public function findRecent(int $limit = 10): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les séries ayant un certain nombre minimum de saisons.
     *
     * @param int $minSeasons Le nombre minimum de saisons.
     * @return Serie[] Retourne les séries ayant au moins le nombre de saisons spécifié.
     */
    public function findByMinimumSeasons(int $minSeasons): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.numberOfSeasons >= :minSeasons')
            ->setParameter('minSeasons', $minSeasons)
            ->orderBy('s.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
