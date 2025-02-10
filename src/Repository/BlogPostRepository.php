<?php

namespace App\Repository;

use App\Entity\BlogPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlogPost>
 */
class BlogPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogPost::class);
    }

    /**
     * @return BlogPost[] Returns an array of BlogPost objects
     */
    public function findPublishedNews($limit = 10, $offset = 1): array
    {
        return $this->createQueryBuilder('b')
            ->addSelect('t')
            ->leftJoin('b.type', 't')
            ->orWhere('t.title = :a')
            ->orWhere('t.title = :b')
            ->orWhere('t.title = :c')
            ->setParameter('a', "Neuigkeiten")
            ->setParameter('b', "Pressemitteilung")
            ->setParameter('c', "Meldung")
            ->orderBy('b.updatedAt', 'DESC')
            ->setFirstResult($limit * ($offset - 1))
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return BlogPost[] Returns an array of BlogPost objects
     */
    public function findPublishedBlogPosts(array $types = [], $limit = 6, int $offset = 1): array
    {
        if(empty($types)) {
            $types = explode(" ", "Blog Erfahrungsbericht Interview Podcast Fachartikel");
        }
        return $this->createQueryBuilder('b')
            ->addSelect('t')
            ->leftJoin('b.type', 't')
            ->orWhere('t.title IN (:a)')
            ->setParameter('a', $types)
            ->orderBy('b.updatedAt', 'DESC')
            ->setFirstResult(($offset - 1) * $limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findLatestByType(string $value): ?BlogPost
    {
        return $this->createQueryBuilder('b')
            ->addSelect('t')
            ->leftJoin('b.type', 't')
            ->andWhere('b.isPublished = :a')
            ->andWhere('b.isLeadPost = :b')
            ->setParameter('b', false)
            ->andWhere('t.title = :val')
            ->setParameter('a', true)
            ->setParameter('val', $value)
            ->orderBy('b.updatedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findLatestLead(): ?BlogPost
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.isPublished = :a')
            ->andWhere('b.isLeadPost = :a')
            ->setParameter('a', true)
            ->orderBy('b.updatedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @return BlogPost[] Returns an array of BlogPost objects
     */
    public function findByQuery(string $query): array
    {
        return $this->createQueryBuilder('p')
            ->orWhere('p.title LIKE :query')
            ->orWhere('p.content LIKE :query')
            ->setParameter('query', "%$query%")
            ->orderBy('p.type', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPreviousPost(int $id, array $types = []): ?BlogPost
    {
        if(empty($types)) {
            $types = explode(" ", "Blog Erfahrungsbericht Interview Podcast Fachartikel");
        }

        return $this->createQueryBuilder('b')
            ->addSelect('t')
            ->leftJoin('b.type', 't')
            ->andWhere('b.isPublished = :a')
            ->andWhere('t.title IN (:types)')
            ->andWhere('b.id < :id')
            ->setParameter('a', true)
            ->setParameter('types', $types)
            ->setParameter('id', $id)
            ->orderBy('b.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findNextPost(int $id, array $types = []): ?BlogPost
    {
        if(empty($types)) {
            $types = explode(" ", "Blog Erfahrungsbericht Interview Podcast Fachartikel");
        }

        return $this->createQueryBuilder('b')
            ->addSelect('t')
            ->leftJoin('b.type', 't')
            ->andWhere('b.isPublished = :a')
            ->andWhere('t.title IN (:types)')
            ->andWhere('b.id > :id')
            ->setParameter('a', true)
            ->setParameter('types', $types)
            ->setParameter('id', $id)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

}
