<?php

namespace App\Repository;

use App\Entity\Auteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Auteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Auteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Auteur[]    findAll()
 * @method Auteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Auteur::class);
    }

    // /**
    //  * @return Auteur[] Returns an array of Auteur objects
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
    public function findOneBySomeField($value): ?Auteur
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
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
        return $this->createQueryBuilder('a')
            // On va d'abord chercher dans la table 'p', dans la colonne 'nom' la valeur enregistrée)
            ->andWhere("a.nom LIKE :val")
            // On définit comme paramètres que la valeur 'val' présente en BDD doit correspondre à la valeur recherchée par la méthode 'Search'.
            ->setParameter('val', "%$value%")
            ->getQuery()
            ->getResult();
    }
}