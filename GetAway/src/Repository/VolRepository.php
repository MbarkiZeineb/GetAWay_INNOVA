<?php

namespace App\Repository;

use App\Entity\Vol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vol|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vol|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vol[]    findAll()
 * @method Vol[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vol::class);
    }


    public function countByDate()
    {
        $query = $this->createQueryBuilder('a')
            ->select('SUBSTRING(a.dateDepart, 1, 10) as date, COUNT(a) as count')
            ->groupBy('date');
        return $query->getQuery()->getResult();

    }


    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Vol $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function TriA()
    {

        return $this->createQueryBuilder('a')
            ->orderBy('a.prix', 'ASC')
            ->getQuery()
            ->getResult();

        return $query->getResult();

    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Vol $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Vol[] Returns an array of Vol objects
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
    public function findOneBySomeField($value): ?Vol
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByvilledest($villeArrivee){
        return $this->getEntityManager()->createQuery(
            'SELECT c
                    FROM App\Entity\Vol c
                    WHERE c.villeArrivee LIKE :villeArrivee'
        )
            ->setParameter('villeArrivee', '%'.$villeArrivee.'%')
            ->getResult();
    }

    public function listByidv($id)
    {
        return $this->createQueryBuilder('v')
            ->join('v.idAvion','a')
            ->addSelect('a')
            ->where('a.idAgence=:idAvion')
            ->setParameter('idAvion',$id)
            ->getQuery()->getResult();

    }
}