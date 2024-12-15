<?php

namespace App\Repository;

use App\Entity\Season;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Season>
 */
class SeasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Season::class);
    }

    /**
     * Trouve les saisons par série ID.
     *
     * @param int $serieId L'identifiant de la série.
     * @return Season[] Retourne un tableau de saisons pour la série donnée.
     */
    public function findBySerieId(int $serieId): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.serie = :serieId')
            ->setParameter('serieId', $serieId)
            ->orderBy('s.number', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les saisons publiées après une date donnée.
     *
     * @param \DateTime $date La date limite.
     * @return Season[] Retourne les saisons publiées après la date spécifiée.
     */
    public function findSeasonsAfterDate(\DateTime $date): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.releaseDate > :date')
            ->setParameter('date', $date)
            ->orderBy('s.releaseDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche des saisons contenant un mot-clé dans leur description.
     *
     * @param string $keyword Le mot-clé recherché.
     * @return Season[] Retourne les saisons correspondant au mot-clé.
     */
    public function searchByKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.description LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%')
            ->orderBy('s.number', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve la dernière saison ajoutée pour une série.
     *
     * @param int $serieId L'identifiant de la série.
     * @return Season|null Retourne la dernière saison ou null si aucune n'est trouvée.
     */
    public function findLastSeasonBySerie(int $serieId): ?Season
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.serie = :serieId')
            ->setParameter('serieId', $serieId)
            ->orderBy('s.number', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
