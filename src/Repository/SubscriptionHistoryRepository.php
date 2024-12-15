<?php

namespace App\Repository;

use App\Entity\SubscriptionHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SubscriptionHistory>
 */
class SubscriptionHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscriptionHistory::class);
    }

    /**
     * Récupère l'historique d'abonnement d'un utilisateur spécifique.
     *
     * @param int $userId ID de l'utilisateur.
     * @return SubscriptionHistory[] Liste des historiques d'abonnement.
     */
    public function findByUser(int $userId): array
    {
        return $this->createQueryBuilder('sh')
            ->andWhere('sh.subscriber = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('sh.startAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère l'historique d'un abonnement spécifique.
     *
     * @param int $subscriptionId ID de l'abonnement.
     * @return SubscriptionHistory[] Liste des historiques pour cet abonnement.
     */
    public function findBySubscription(int $subscriptionId): array
    {
        return $this->createQueryBuilder('sh')
            ->andWhere('sh.subscription = :subscriptionId')
            ->setParameter('subscriptionId', $subscriptionId)
            ->orderBy('sh.startAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère le dernier abonnement actif pour un utilisateur spécifique.
     *
     * @param int $userId ID de l'utilisateur.
     * @return SubscriptionHistory|null Dernier abonnement actif ou null si aucun.
     */
    public function findLastActiveByUser(int $userId): ?SubscriptionHistory
    {
        return $this->createQueryBuilder('sh')
            ->andWhere('sh.subscriber = :userId')
            ->andWhere('sh.endAt > :now OR sh.endAt IS NULL') // Si l'abonnement n'a pas de date de fin.
            ->setParameter('userId', $userId)
            ->setParameter('now', new \DateTime())
            ->orderBy('sh.startAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Supprime tous les historiques liés à un utilisateur.
     *
     * @param int $userId ID de l'utilisateur.
     * @return int Nombre d'historiques supprimés.
     */
    public function deleteByUser(int $userId): int
    {
        return $this->createQueryBuilder('sh')
            ->delete()
            ->andWhere('sh.subscriber = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->execute();
    }

    /**
     * Supprime tous les historiques liés à un abonnement.
     *
     * @param int $subscriptionId ID de l'abonnement.
     * @return int Nombre d'historiques supprimés.
     */
    public function deleteBySubscription(int $subscriptionId): int
    {
        return $this->createQueryBuilder('sh')
            ->delete()
            ->andWhere('sh.subscription = :subscriptionId')
            ->setParameter('subscriptionId', $subscriptionId)
            ->getQuery()
            ->execute();
    }
}
