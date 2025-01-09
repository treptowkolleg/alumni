<?php

namespace App\Repository;

use App\Entity\BlogType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlogType>
 */
class BlogTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogType::class);
    }

    /**
     * @return BlogType[] Returns an array of BlogPost objects
     */
    public function findByTypes(string $types): array
    {
        $types = explode(" ", $types);
        return $this->createQueryBuilder('b')
            ->orWhere('b.title IN (:a)')
            ->setParameter('a', $types)
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return BlogType[] Returns an array of BlogType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BlogType
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
