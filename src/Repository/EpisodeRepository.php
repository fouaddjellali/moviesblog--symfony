<?php

namespace App\Repository;

use App\Entity\Episode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Episode>
 */
class EpisodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Episode::class);
    }

    /**
     * Retourne les épisodes d'une saison donnée, triés par numéro d'épisode.
     *
     * @param int $seasonId L'identifiant de la saison.
     * @return Episode[] Retourne un tableau d'épisodes triés par numéro.
     */
    public function findBySeason(int $seasonId): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.season = :seasonId')
            ->setParameter('seasonId', $seasonId)
            ->orderBy('e.number', 'ASC') // Trier par numéro d'épisode
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les épisodes d'une série donnée, triés par saison et numéro d'épisode.
     *
     * @param int $serieId L'identifiant de la série.
     * @return Episode[] Retourne un tableau d'épisodes triés.
     */
    public function findBySerie(int $serieId): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.season', 's')
            ->andWhere('s.serie = :serieId')
            ->setParameter('serieId', $serieId)
            ->orderBy('s.number', 'ASC') // Trier par numéro de saison
            ->addOrderBy('e.number', 'ASC') // Puis par numéro d'épisode
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les épisodes récents ajoutés, limités à un certain nombre.
     *
     * @param int $limit Le nombre maximum d'épisodes à retourner.
     * @return Episode[] Retourne un tableau des derniers épisodes.
     */
    public function findRecentEpisodes(int $limit = 10): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.createdAt', 'DESC') // Trier par date de création
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
