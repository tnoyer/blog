<?php

namespace App\Repository;

use App\Entity\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articles::class);
    }

    /**
     * @return count of articles
     */
    public function countAllArticles()
    {
        $qb = $this->createQueryBuilder('a');
        $qb->select('COUNT(a.id) as value');
        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param $page
     * @param $limit
     * @param null $filters
     * @return all articles per page
     */
    public function getPaginatedArticles($page, $limit, $filters = null)
    {
        $query = $this->createQueryBuilder('a');
        $query->join('a.categories', 'c');
        if($filters != null){
            $query->where('c.id IN(:cats)')
                ->setParameter('cats', array_values($filters));
        }
        $query->orderBy('a.created_at')
            ->setFirstResult(($page * $limit) - $limit) //OFFSET
            ->setMaxResults($limit) //LIMIT
        ;
        return $query->getQuery()->getResult();
    }

    /**
     * @param null $filters
     * @return number of articles
     */
    public function getTotalArticles($filters = null){
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(DISTINCT a)')
            ->join('a.categories', 'c');
        // On filtre les données
        if($filters != null){
            $query->andWhere('c.id IN(:cats)')
                ->setParameter(':cats', array_values($filters));
        }
        return $query->getQuery()->getSingleScalarResult();
    }

    // /**
    //  * @return Articles[] Returns an array of Articles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Articles
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
