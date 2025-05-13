<?php

namespace App\Repository;

use App\Entity\User;
use App\Enums\MessageVisibilityScope;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function createVisibleRecipientsQuery(User $me): QueryBuilder
    {
        return $this->createQueryBuilder('u')
            ->addSelect('up')
            ->join('u.userProfiles','up')
            ->where('u != :me')
            ->andWhere('u.isContactable = true')
            ->andWhere('
            u.messageVisibilityScope = :all
            OR (u.messageVisibilityScope = :sameSchool AND u.school = :mySchool)
            OR (
                u.messageVisibilityScope = :known
                AND EXISTS (
                    SELECT 1 FROM App\Entity\UserProfile up2
                    JOIN up2.friends f
                    WHERE up2 = up AND f = :meProfile
                )
            )
        ')
            ->setParameter('me', $me)
            ->setParameter('mySchool', $me->getSchool())
            ->setParameter('meProfile', $me->getUserProfiles()->first())
            ->setParameter('all', MessageVisibilityScope::all->value)
            ->setParameter('sameSchool', MessageVisibilityScope::SameSchool->value)
            ->setParameter('known', MessageVisibilityScope::ContactsOnly->value)
            ->orderBy('u.lastname', 'ASC');
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
