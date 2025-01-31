<?php

namespace App\Repository;

use App\Entity\BlogPost;
use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @return BlogPost[] Returns an array of BlogPost objects
     */
    public function findByBlogType(array $types): array
    {
        return $this->createQueryBuilder('b')
            ->addSelect('t')
            ->leftJoin('b.type', 't')
            ->orWhere('t.title IN (:a)')
            ->setParameter('a', $types)
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return Event[] Returns an array of Event objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * @return Event[] Returns an array of Event objects
     */
    public function findBySearchQuery(?array $filterValues = [],int $results = 10,  int $offset = 1): array
    {
        $query = $this->createQueryBuilder('e');


        if(isset($filterValues['type'])){
            $types = $filterValues['type'];
            $query->andWhere('e.type IN (:types)');
            $query->setParameter('types', $types);
        }

        if(isset($filterValues['school'])){
            $schools = $filterValues['school'];
            $query->addSelect('s');
            $query->leftJoin('e.school', 's');
            $query->andWhere('s.id IN (:schools)');
            $query->setParameter('schools', $schools);
        }

        if(isset($filterValues['period']) and $filterValues['period'] != ""){
            $period = $filterValues['period'];
            $date = new \DateTime("$period months");

            if($period > 0) {
                $now = new \DateTime();
                $query->andWhere('e.endDate BETWEEN :now AND :period');
                $query->setParameter('now', $now);
            } else {
                $yesterday = new \DateTime("-1 days");
                $query->andWhere('e.endDate BETWEEN :period AND :yesterday');
                $query->setParameter('yesterday', $yesterday);
            }
            $query->setParameter('period', $date);

        }

        $query->orderBy('e.startDate', 'DESC');

        return $query->getQuery()->setMaxResults(50)->setFirstResult($results*($offset-1))->getResult();
    }
}
