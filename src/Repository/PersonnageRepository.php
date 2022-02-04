<?php

namespace App\Repository;

use App\Entity\Personnage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Personnage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Personnage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Personnage[]    findAll()
 * @method Personnage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonnageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personnage::class);
    }

    // /**
    //  * @return Personnage[] Returns an array of Personnage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Personnage
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // Fonction qui prend n'importe quelle valeur sous forme de 'string'
    public function findBySearch(string $value): array
    {
        // On utilise la valeur retournée pour créer une requête SQL grâce à la méthode 'createQueryBuilder' de l'ORM Doctrine.
        return $this->createQueryBuilder('p')
            // On va d'abord chercher dans la table 'p', dans la colonne 'nom' la valeur enregistrée)
            ->andWhere("p.nom LIKE :val")
            // On définit comme paramètres que la valeur 'val' présente en BDD doit correspondre à la valeur recherchée par la méthode 'Search'.
            ->setParameter('val', "%$value%")
            ->getQuery()
            ->getResult();
    }
}