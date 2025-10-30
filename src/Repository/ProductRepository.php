<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Récupère les nouveaux produits (les 3 plus récents)
     */
    public function findNewProducts(int $limit = 3): array
{
    return $this->createQueryBuilder('p')
        ->orderBy('p.createdAt', 'DESC')
        ->addOrderBy('p.id', 'DESC') // Tri secondaire par ID
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
}

    /**
     * Récupère les produits recommandés (les 6 mieux stockés)
     */
    public function findRecommendedProducts(int $limit = 6): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.stock > 0')
            ->orderBy('p.stock', 'DESC')
            ->addOrderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les produits par catégorie
     */
    public function findByCategory(string $categorySlug): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.category', 'c')
            ->where('c.slug = :slug')
            ->andWhere('p.stock > 0')
            ->setParameter('slug', $categorySlug)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche des produits par nom ou description
     */
    public function searchProducts(string $query): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.name LIKE :query')
            ->orWhere('p.description LIKE :query')
            ->andWhere('p.stock > 0')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}