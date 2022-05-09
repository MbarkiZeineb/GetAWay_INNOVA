<?php

namespace App\Repository;

use App\Entity\Activite;
use App\Entity\Activitelike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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

    public function getCountForPost(Activite $activite)
    {
        return $this->createQueryBuilder('l')
            ->select('COUNT(l) AS likes')
            ->andWhere('l.act = :activite')
            ->setParameter('activite', $activite)
            ->getQuery()
            ->getSingleScalarResult();

    }


    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Activitelike $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Activitelike $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
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
