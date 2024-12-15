<?php

namespace App\Repository;

use App\Entity\WatchHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WatchHistory>
 */
class WatchHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WatchHistory::class);
    }

    /**
     * Récupère l'historique de visionnage d'un utilisateur.
     *
     * @param int $userId ID de l'utilisateur.
     * @return WatchHistory[] Liste des historiques de visionnage.
     */
    public function findByUser(int $userId): array
    {
        return $this->createQueryBuilder('wh')
            ->andWhere('wh.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('wh.watchedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère l'historique de visionnage pour un média spécifique.
     *
     * @param int $mediaId ID du média.
     * @return WatchHistory[] Liste des historiques pour ce média.
     */
    public function findByMedia(int $mediaId): array
    {
        return $this->createQueryBuilder('wh')
            ->andWhere('wh.media = :mediaId')
            ->setParameter('mediaId', $mediaId)
            ->orderBy('wh.watchedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère le dernier historique de visionnage d'un utilisateur pour un média spécifique.
     *
     * @param int $userId ID de l'utilisateur.
     * @param int $mediaId ID du média.
     * @return WatchHistory|null Dernier historique de visionnage ou null si aucun.
     */
    public function findLastByUserAndMedia(int $userId, int $mediaId): ?WatchHistory
    {
        return $this->createQueryBuilder('wh')
            ->andWhere('wh.user = :userId')
            ->andWhere('wh.media = :mediaId')
            ->setParameter('userId', $userId)
            ->setParameter('mediaId', $mediaId)
            ->orderBy('wh.watchedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Supprime l'historique de visionnage d'un utilisateur.
     *
     * @param int $userId ID de l'utilisateur.
     * @return int Nombre d'historiques supprimés.
     */
    public function deleteByUser(int $userId): int
    {
        return $this->createQueryBuilder('wh')
            ->delete()
            ->andWhere('wh.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->execute();
    }

    /**
     * Supprime l'historique de visionnage pour un média spécifique.
     *
     * @param int $mediaId ID du média.
     * @return int Nombre d'historiques supprimés.
     */
    public function deleteByMedia(int $mediaId): int
    {
        return $this->createQueryBuilder('wh')
            ->delete()
            ->andWhere('wh.media = :mediaId')
            ->setParameter('mediaId', $mediaId)
            ->getQuery()
            ->execute();
    }
}
