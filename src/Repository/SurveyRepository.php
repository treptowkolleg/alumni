<?php

namespace App\Repository;

use App\Entity\Survey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Survey>
 */
class SurveyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Survey::class);
    }

//    /**
//     * @return Survey[] Returns an array of Survey objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findOneByOpen(UserInterface $user): ?Survey
    {
        $qb = $this->createQueryBuilder('s');

        $sub = $this->createQueryBuilder('sub')
            ->select('1')
            ->from('App\Entity\SurveyAnswer', 'a')
            ->where('a.survey = s')
            ->andWhere('a.user = :user')
            ->getDQL();

        $qb->where($qb->expr()->not($qb->expr()->exists($sub)))
            ->setParameter('user', $user)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
