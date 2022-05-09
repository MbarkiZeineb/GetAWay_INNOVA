<?php

namespace App\Repository;

use App\Entity\Voyageorganise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Voyageorganise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voyageorganise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voyageorganise[]    findAll()
 * @method Voyageorganise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoyOrgRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voyageorganise::class);
    }

    // /**
    //  * @return Voyageorganise[] Returns an array of Voyageorganise objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Voyageorganise
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
