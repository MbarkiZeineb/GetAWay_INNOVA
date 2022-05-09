<?php

namespace App\Repository;

use App\Entity\Activitelike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Activitelike|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activitelike|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activitelike[]    findAll()
 * @method Activitelike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivitelikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activitelike::class);
    }

    // /**
    //  * @return Activitelike[] Returns an array of Activitelike objects
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
    public function findOneBySomeField($value): ?Activitelike
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
