<?php

namespace App\Repository;

use App\Entity\Legende;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Legende|null find($id, $lockMode = null, $lockVersion = null)
 * @method Legende|null findOneBy(array $criteria, array $orderBy = null)
 * @method Legende[]    findAll()
 * @method Legende[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LegendeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Legende::class);
    }

    // /**
    //  * @return Legende[] Returns an array of Legende objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Legende
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
