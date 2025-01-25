<?php

namespace App\Repository;

use App\Entity\UserProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
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
            ->orderBy('RAND()')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return UserProfile[] Returns an array of UserProfile objects
     */
    public function findByFriends(array $value, ?string $query = ""): array
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
            ->orderBy('RAND()')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return UserProfile[] Returns an array of UserProfile objects
     */
    public function findBySearchQuery(bool|string $name = false, bool|string $firstname = false): array
    {
        $query = $this->createQueryBuilder('up')->addSelect('u')->leftJoin('up.user', 'u');

        // TODO: Kombinieren mit Score aus normalem Wort. Erst hier splitten nach SoundEx

        if ($name and $firstname) {
            $query->andWhere('LEVENSHTEIN(u.firstnameSoundEx, :firstname,3) <= 3');
            $query->andWhere('LEVENSHTEIN(u.lastnameSoundEx, :name,4) <= 4');
            $query->setParameter('firstname', $firstname);
            $query->setParameter('name', $name);
            $query->orderBy('LEVENSHTEIN(u.firstnameSoundEx, :name,3)', 'DESC');
        } elseif ($name) {
            $query->andWhere('LEVENSHTEIN(u.firstnameSoundEx, :name,2) <= 2');
            $query->setParameter('name', $name);
            $query->orderBy('LEVENSHTEIN(u.firstnameSoundEx, :name,2)', 'DESC');
        } else {
            $query->orderBy('RAND()');
        }



        return $query->getQuery()->setMaxResults(9)->getResult();
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
