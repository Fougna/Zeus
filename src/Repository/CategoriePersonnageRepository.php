<?php

namespace App\Repository;

use App\Entity\CategoriePersonnage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoriePersonnage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriePersonnage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriePersonnage[]    findAll()
 * @method CategoriePersonnage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriePersonnageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriePersonnage::class);
    }

    // /**
    //  * @return CategoriePersonnage[] Returns an array of CategoriePersonnage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoriePersonnage
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
