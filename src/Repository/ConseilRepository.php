<?php

namespace App\Repository;

use App\Entity\Conseil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conseil>
 *
 * @method Conseil|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conseil|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conseil[]    findAll()
 * @method Conseil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConseilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conseil::class);
    }

//    /**
//     * @return Conseil[] Returns an array of Conseil objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Conseil
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function conseilsCount(): int
{
    return $this->createQueryBuilder('c')
        ->select('COUNT(c.idConseil)')
        ->getQuery()
        ->getSingleScalarResult();
}

public function getConseilCountsByType(): array
{
    return $this->createQueryBuilder('c')
        ->select('tc.nomtypec as typeConseil', 'COUNT(c.idConseil) as count')
        ->leftJoin('c.idTypec', 'tc')
        ->groupBy('tc.nomtypec')
        ->getQuery()
        ->getResult();
}

    public function findByNomConseil(string $query): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.nomConseil LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->getQuery()
            ->getResult();
    }


    public function findAllSortedByCategoryAsc(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.idTypec', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findLatestConseilDateCreation(): ?\DateTimeInterface
    {
        $result = $this->createQueryBuilder('c')
            ->select('c.datecreation')
            ->orderBy('c.datecreation', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
            
        return $result['datecreation'] ?? null;
    }



   


}
