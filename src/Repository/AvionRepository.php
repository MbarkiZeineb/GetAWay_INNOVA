<?php

namespace App\Repository;

use App\Entity\Avion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Avion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Avion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Avion[]    findAll()
 * @method Avion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avion::class);
    }

    // /**
    //  * @return Avion[] Returns an array of Avion objects
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
    public function findOneBySomeField($value): ?Avion
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
