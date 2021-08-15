<?php

namespace App\Repository;

use App\Entity\InventaireList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InventaireList|null find($id, $lockMode = null, $lockVersion = null)
 * @method InventaireList|null findOneBy(array $criteria, array $orderBy = null)
 * @method InventaireList[]    findAll()
 * @method InventaireList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InventaireListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InventaireList::class);
    }

    // /**
    //  * @return InventaireList[] Returns an array of InventaireList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InventaireList
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
