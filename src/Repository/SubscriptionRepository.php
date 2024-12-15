<?php

namespace App\Repository;

use App\Entity\Subscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subscription>
 */
class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    /**
     * Récupère tous les abonnements triés par prix croissant.
     *
     * @return Subscription[] Retourne une liste d'abonnements triée par prix.
     */
    public function findAllOrderedByPrice(): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.price', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les abonnements avec une durée minimale.
     *
     * @param int $minDuration Durée minimale en mois.
     * @return Subscription[] Retourne les abonnements filtrés par durée.
     */
    public function findByMinimumDuration(int $minDuration): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.duration >= :minDuration')
            ->setParameter('minDuration', $minDuration)
            ->orderBy('s.duration', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les abonnements dans une fourchette de prix.
     *
     * @param float $minPrice Prix minimum.
     * @param float $maxPrice Prix maximum.
     * @return Subscription[] Retourne les abonnements filtrés par prix.
     */
    public function findByPriceRange(float $minPrice, float $maxPrice): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.price BETWEEN :minPrice AND :maxPrice')
            ->setParameter('minPrice', $minPrice)
            ->setParameter('maxPrice', $maxPrice)
            ->orderBy('s.price', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère un abonnement spécifique par son nom.
     *
     * @param string $name Nom de l'abonnement.
     * @return Subscription|null Retourne l'abonnement ou null si introuvable.
     */
    public function findByName(string $name): ?Subscription
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Récupère les abonnements premium (prix supérieur à un seuil défini).
     *
     * @param float $threshold Prix seuil pour les abonnements premium.
     * @return Subscription[] Retourne les abonnements premium.
     */
    public function findPremiumSubscriptions(float $threshold = 20.0): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.price > :threshold')
            ->setParameter('threshold', $threshold)
            ->orderBy('s.price', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
