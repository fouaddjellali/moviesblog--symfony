<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * Retourne les commentaires associés à un média donné.
     *
     * @param int $mediaId L'identifiant du média.
     * @return Comment[] Retourne un tableau de commentaires.
     */
    public function findByMedia(int $mediaId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.media = :mediaId')
            ->setParameter('mediaId', $mediaId)
            ->orderBy('c.createdAt', 'DESC') // Trier par date de création (décroissante)
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les commentaires validés.
     *
     * @return Comment[] Retourne un tableau de commentaires validés.
     */
    public function findValidated(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.status = :status')
            ->setParameter('status', 'validated') // Modifier selon vos constantes ou valeurs exactes
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
