<?php

namespace App\Repository;

use App\Entity\Language;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Language>
 */
class LanguageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Language::class);
    }

    /**
     * Trouve les langues par leur code.
     *
     * @param string $code Le code de la langue (ex : 'fr', 'en').
     * @return Language|null Retourne une langue ou null si aucune n'est trouvée.
     */
    public function findByCode(string $code): ?Language
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Retourne toutes les langues triées par nom.
     *
     * @return Language[] Retourne un tableau d'objets `Language` trié par nom.
     */
    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('l')
            ->orderBy('l.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche les langues par mot-clé dans le nom.
     *
     * @param string $keyword Le mot-clé à rechercher.
     * @return Language[] Retourne les langues correspondant au mot-clé.
     */
    public function searchByNameKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.name LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%')
            ->orderBy('l.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les langues utilisées par un certain nombre de médias.
     *
     * @param int $minUsage Nombre minimal de médias utilisant cette langue.
     * @return Language[] Retourne les langues filtrées par leur usage.
     */
    public function findLanguagesByMediaUsage(int $minUsage): array
    {
        return $this->createQueryBuilder('l')
            ->join('l.media', 'm')
            ->groupBy('l.id')
            ->having('COUNT(m.id) >= :minUsage')
            ->setParameter('minUsage', $minUsage)
            ->orderBy('COUNT(m.id)', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
