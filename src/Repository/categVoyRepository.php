<?php

namespace App\Repository;

use App\Entity\Categorievoy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Categorievoy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorievoy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorievoy[]    findAll()
 * @method Categorievoy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class categVoyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorievoy::class);
    }
}