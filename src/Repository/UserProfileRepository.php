<?php

namespace App\Repository;

use App\Entity\UserProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserProfile>
 */
class UserProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProfile::class);
    }



    /**
     * @return UserProfile[] Returns an array of UserProfile objects
     */
    public function findBySchool(array $value, ?string $query = ""): array
    {
        return $this->createQueryBuilder('up')
            ->addSelect('s')
            ->addSelect('u')
            ->leftJoin('up.user', 'u')
            ->leftJoin('u.school', 's')
            ->andWhere('u.firstname LIKE :query')
            ->orWhere('u.lastname LIKE :query')
            ->andWhere('s.title IN (:val)')
            ->setParameter('val', $value)
            ->setParameter('query', "%$query%")
            ->setMaxResults(30)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return UserProfile[] Returns an array of UserProfile objects
     */
    public function findBySearchQuery(?string $query = ""): array
    {
        return $this->createQueryBuilder('up')
            ->addSelect('u')
            ->leftJoin('up.user', 'u')
            ->andWhere('u.firstname LIKE :query')
            ->orWhere('u.lastname LIKE :query')
            ->setParameter('query', "%$query%")
            ->setMaxResults(30)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return UserProfile[] Returns an array of UserProfile objects
     */
    public function findExamDates(array $value = []): array
    {
        $query = $this->createQueryBuilder('up')->select('up.outYear, COUNT(up.outYear) AS total');
        if(!empty($value)) {
            $query->orWhere('up.outYear IN (:val)')->setParameter('val', $value);
        }
        return $query->groupBy('up.outYear')->getQuery()->getResult();
    }

//    public function findOneBySomeField($value): ?UserProfile
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
