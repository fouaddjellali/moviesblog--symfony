<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Trouve les utilisateurs par rôle.
     *
     * @param string $role Le rôle recherché (par exemple : "ROLE_ADMIN").
     * @return User[] Retourne un tableau d'utilisateurs ayant le rôle spécifié.
     */
    public function findByRole(string $role): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('JSON_CONTAINS(u.roles, :role) = 1')
            ->setParameter('role', json_encode($role))
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les utilisateurs récemment inscrits.
     *
     * @param int $days Le nombre de jours pour considérer les inscriptions récentes.
     * @return User[] Retourne un tableau d'utilisateurs inscrits récemment.
     */
    public function findRecentUsers(int $days = 7): array
    {
        $date = new \DateTime();
        $date->modify("-$days days");

        return $this->createQueryBuilder('u')
            ->andWhere('u.createdAt >= :recentDate')
            ->setParameter('recentDate', $date)
            ->orderBy('u.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les utilisateurs ayant confirmé leur compte.
     *
     * @return User[] Retourne un tableau d'utilisateurs avec le statut actif.
     */
    public function findActiveUsers(): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche les utilisateurs contenant un mot-clé dans leur email ou nom d'utilisateur.
     *
     * @param string $keyword Le mot-clé recherché.
     * @return User[] Retourne un tableau d'utilisateurs correspondant au mot-clé.
     */
    public function searchUsers(string $keyword): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email LIKE :keyword OR u.username LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

