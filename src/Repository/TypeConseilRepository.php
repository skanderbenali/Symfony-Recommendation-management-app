<?php

namespace App\Repository;

use App\Entity\Typeconseil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Typeconseil>
 *
 * @method Typeconseil|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typeconseil|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typeconseil[]    findAll()
 * @method Typeconseil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeConseilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typeconseil::class);
    }

//    /**
//     * @return Typeconseil[] Returns an array of Typeconseil objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Typeconseil
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findByCategory($name)
{
    return $this->createQueryBuilder('c')
                ->andWhere('a.name = :name')
                ->setParameter('name' , $name)
                ->getQuery()
                ->getResult();
}

}
