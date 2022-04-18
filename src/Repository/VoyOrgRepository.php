<?php
namespace App\Repository;
use App\Entity\Voyageorganise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;

/**
 * @method \App\Entity\Voyageorganise|null find($id, $lockMode = null, $lockVersion = null)
 * @method \App\Entity\Voyageorganise|null findOneBy(array $criteria, array $orderBy = null)
 * @method \App\Entity\Voyageorganise[]    findAll()
 * @method \App\Entity\Voyageorganise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class VoyOrgRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voyageorganise::class);
    }



    public function findByvilledest($villedest){
        return $this->getEntityManager()->createQuery(
                   'SELECT c
                    FROM App\Entity\Voyageorganise c
                    WHERE c.villedest LIKE :villedest'
            )
            ->setParameter('villedest', '%'.$villedest.'%')
            ->getResult();
    }


}