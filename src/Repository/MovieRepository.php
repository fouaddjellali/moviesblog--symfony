<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * Récupère les films les plus populaires en fonction d'une métrique donnée.
     *
     * @param string $metric Le champ utilisé pour trier les films (par exemple, `rating` ou `views`).
     * @param int $limit Le nombre maximum de films à retourner.
     * @return Movie[] Retourne un tableau de films triés par popularité.
     */
    public function findPopular(string $metric = 'rating', int $limit = 10): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.' . $metric, 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche des films contenant un mot-clé dans leur titre ou description.
     *
     * @param string $keyword Le mot-clé recherché.
     * @return Movie[] Retourne les films correspondant au mot-clé.
     */
    public function searchByKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.title LIKE :keyword OR m.description LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%')
            ->orderBy('m.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les films ajoutés récemment.
     *
     * @param int $limit Le nombre maximum de films à retourner.
     * @return Movie[] Retourne les films ajoutés récemment, triés par date.
     */
    public function findRecent(int $limit = 10): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les films sortis entre deux dates.
     *
     * @param \DateTime $startDate La date de début.
     * @param \DateTime $endDate La date de fin.
     * @return Movie[] Retourne les films sortis dans cette période.
     */
    public function findByReleaseDateRange(\DateTime $startDate, \DateTime $endDate): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.releaseDate BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->orderBy('m.releaseDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les films ayant une durée supérieure à une valeur donnée.
     *
     * @param int $minDuration La durée minimale en minutes.
     * @return Movie[] Retourne les films ayant une durée minimale spécifiée.
     */
    public function findByMinimumDuration(int $minDuration): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.duration >= :minDuration')
            ->setParameter('minDuration', $minDuration)
            ->orderBy('m.duration', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
