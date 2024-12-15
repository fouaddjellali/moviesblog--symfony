<?php

namespace App\Repository;

use App\Entity\PlaylistSubscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlaylistSubscription>
 */
class PlaylistSubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlaylistSubscription::class);
    }

    /**
     * Récupère toutes les souscriptions d'une playlist spécifique.
     *
     * @param int $playlistId ID de la playlist.
     * @return PlaylistSubscription[] Liste des souscriptions liées à la playlist.
     */
    public function findByPlaylist(int $playlistId): array
    {
        return $this->createQueryBuilder('ps')
            ->andWhere('ps.playlist = :playlistId')
            ->setParameter('playlistId', $playlistId)
            ->orderBy('ps.subscribedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère toutes les souscriptions d'un utilisateur spécifique.
     *
     * @param int $userId ID de l'utilisateur.
     * @return PlaylistSubscription[] Liste des souscriptions liées à l'utilisateur.
     */
    public function findByUser(int $userId): array
    {
        return $this->createQueryBuilder('ps')
            ->andWhere('ps.subscriber = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('ps.subscribedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Vérifie si un utilisateur est abonné à une playlist spécifique.
     *
     * @param int $userId ID de l'utilisateur.
     * @param int $playlistId ID de la playlist.
     * @return bool Retourne true si l'utilisateur est abonné, sinon false.
     */
    public function isUserSubscribedToPlaylist(int $userId, int $playlistId): bool
    {
        $result = $this->createQueryBuilder('ps')
            ->select('count(ps.id)')
            ->andWhere('ps.subscriber = :userId')
            ->andWhere('ps.playlist = :playlistId')
            ->setParameter('userId', $userId)
            ->setParameter('playlistId', $playlistId)
            ->getQuery()
            ->getSingleScalarResult();

        return (int)$result > 0;
    }

    /**
     * Supprime toutes les souscriptions liées à une playlist.
     *
     * @param int $playlistId ID de la playlist.
     * @return int Nombre de souscriptions supprimées.
     */
    public function deleteByPlaylist(int $playlistId): int
    {
        return $this->createQueryBuilder('ps')
            ->delete()
            ->andWhere('ps.playlist = :playlistId')
            ->setParameter('playlistId', $playlistId)
            ->getQuery()
            ->execute();
    }

    /**
     * Supprime toutes les souscriptions liées à un utilisateur.
     *
     * @param int $userId ID de l'utilisateur.
     * @return int Nombre de souscriptions supprimées.
     */
    public function deleteByUser(int $userId): int
    {
        return $this->createQueryBuilder('ps')
            ->delete()
            ->andWhere('ps.subscriber = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->execute();
    }
}
