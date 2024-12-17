<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }
    /**
     * Retourne une liste des catégories populaires.
     *
     * @return Category[] Returns an array of popular Category objects
     */
    public function findPopular(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC') // Exemple de tri par ID, ajustez selon vos critères
            ->setMaxResults(10) // Limite de 10 résultats, ajustable selon le besoin
            ->getQuery()
            ->getResult();
    }
}
