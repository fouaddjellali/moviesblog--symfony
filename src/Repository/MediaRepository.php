<?php

namespace App\Repository;

use App\Entity\Media;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Media>
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Media::class);
    }

    /**
     * Recherche les médias par catégorie.
     *
     * @param string $categoryId L'ID de la catégorie.
     * @return Media[] Retourne un tableau de médias appartenant à la catégorie donnée.
     */
    public function findByCategory(string $categoryId): array
    {
        return $this->createQueryBuilder('m')
            ->join('m.categories', 'c')
            ->andWhere('c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('m.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche les médias par popularité.
     *
     * @param string $metric La métrique de popularité (ex. `views` ou `rating`).
     * @param int $limit Le nombre maximum de résultats.
     * @return Media[] Retourne les médias triés par popularité.
     */
    public function findPopular(string $metric = 'views', int $limit = 10): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.' . $metric, 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche les médias récents.
     *
     * @param int $limit Le nombre maximum de résultats.
     * @return Media[] Retourne les médias ajoutés récemment.
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
     * Recherche les médias contenant un mot-clé dans le titre ou la description.
     *
     * @param string $keyword Le mot-clé à rechercher.
     * @return Media[] Retourne les médias correspondant à la recherche.
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
     * Récupère les médias sortis entre deux dates.
     *
     * @param \DateTime $startDate La date de début.
     * @param \DateTime $endDate La date de fin.
     * @return Media[] Retourne les médias sortis dans la plage de dates.
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
}
