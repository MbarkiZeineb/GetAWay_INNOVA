<?php

namespace App\Repository;

use App\Entity\Avion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Avion $entity, bool $flush = true): void
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
    public function remove(Avion $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
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

    public function TriA()
    {

        return $this->createQueryBuilder('a')
            ->orderBy('a.nbrPlace','ASC')
            ->getQuery()
            ->getResult();

        return $query->getResult();

    }

    public function listByida($id)
    {
        return $this->createQueryBuilder('r')
            ->join('r.idAgence','u')
            ->addSelect('u')
            ->where('u.id=:id')
            ->setParameter('id',$id)
            ->getQuery()->getResult();

    }
}