<?php

namespace App\Repository;

use App\Entity\Chat;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chat>
 */
class ChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    /**
     * @return Chat[] Returns an array of Chat objects
     */
    public function findAllChats($user): array
    {
        return $this->createQueryBuilder('c')
            ->orWhere('c.owner = :val')
            ->orWhere('c.participant = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Chat
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function countUnreadMessages(User $user): int
    {
        return $this->createQueryBuilder('c')
            ->addSelect('m')
            ->select('count(m.id)')
            ->join('c.messages', 'm')
            ->andWhere('c.owner = :val')
            ->orWhere('c.participant = :val')
            ->andWhere('m.isRead = :is_read')
            ->andWhere('m.sender != :val')
            ->setParameter('is_read', false)
            ->setParameter('val', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
