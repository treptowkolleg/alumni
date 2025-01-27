<?php

namespace App\Repository;

use App\Entity\UserProfile;
use App\Operator\SoundExpression;
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
    public function findBySearchQuery(
        bool|string $name = false,
        bool|string $firstname = false,
        ?array $schools = [],
        ?array $performanceCourses = [],
        ?string $startYear = null,
        ?string $endYear = null,
        int $offset = 1
    ): array
    {
        $query = $this->createQueryBuilder('up')->addSelect('u')->leftJoin('up.user', 'u');

        if ($name and $firstname) {
            $nameSX = SoundExpression::generate($name);
            $firstnameSX = SoundExpression::generate($firstname);

            $query->where(
                $query->expr()->andX(
                    $query->expr()->orX(
                        $query->expr()->eq('u.firstname', ':firstname'),
                        $query->expr()->lt('LEVENSHTEIN(:firstname, u.firstname,3)', 3)
                    ),
                    $query->expr()->lt('LEVENSHTEIN(:firstnameEx, u.firstnameSoundEx,3)', 1),

                    $query->expr()->orX(
                        $query->expr()->eq('u.lastname', ':name'),
                        $query->expr()->lt('LEVENSHTEIN(:name, u.lastname,3)', 3)
                    ),
                    $query->expr()->lt('LEVENSHTEIN(:nameEx, u.lastnameSoundEx,3)', 1)
                )
            );

            $query->setParameter('firstname', $firstname);
            $query->setParameter('firstnameEx', $firstnameSX);
            $query->setParameter('name', $name);
            $query->setParameter('nameEx', $nameSX);

            $query->orderBy('LEVENSHTEIN(u.firstnameSoundEx, :nameEx,3)', 'DESC');
        } elseif ($name) {
            $nameSX = SoundExpression::generate($name);

            $query->where(
                $query->expr()->orX(
                    $query->expr()->andX(
                        $query->expr()->orX(
                            $query->expr()->eq('u.firstname', ':name'),
                            $query->expr()->lt('LEVENSHTEIN(:name, u.firstname,3)', 3)
                        ),
                        $query->expr()->lt('LEVENSHTEIN(:nameEx, u.firstnameSoundEx,3)', 1)
                    ),
                    $query->expr()->andX(
                        $query->expr()->orX(
                            $query->expr()->eq('u.lastname', ':name'),
                            $query->expr()->lt('LEVENSHTEIN(:name, u.lastname,3)', 3)
                        ),
                        $query->expr()->lt('LEVENSHTEIN(:nameEx, u.lastnameSoundEx,3)', 1)
                    ),
                )
            );
            $query->setParameter('name', $name);
            $query->setParameter('nameEx', $nameSX);

            $query->orderBy('LEVENSHTEIN(u.firstnameSoundEx, :nameEx,3)', 'DESC');
        } else {
            $query->orderBy('RAND()');
        }

        if(!empty($schools)) {
            $query->addSelect('s');
            $query->join('u.school', 's');
            $query->andWhere('s.id IN (:schools)');
            $query->setParameter('schools', $schools);
        }

        if(!empty($performanceCourses)) {
            foreach ($performanceCourses as $key => $course) {
                $query->andWhere("up.performanceCourse LIKE :course$key");
                $query->setParameter("course$key", '%"'.$course.'"%');
            }
        }

        if($startYear) {
            $query->andWhere('up.outYear >= :startYear ');
            $query->setParameter('startYear', $startYear);
        }

        if($endYear) {
            $query->andWhere('up.inYear <= :endYear ');
            $query->setParameter('endYear', $endYear);
        }

        return $query->getQuery()->setFirstResult(9*($offset-1))->getResult();
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

}
