<?php

// src/Repository/ReviewRepository.php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * Retrieve reviews associated with a specified Conseil.
     *
     * @param int $conseilId The ID of the Conseil entity
     * @return Review[] Returns an array of Review entities
     */
    public function findReviewsByConseilId(int $conseilId): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.idConseil = :conseilId')
            ->setParameter('conseilId', $conseilId)
            ->getQuery()
            ->getResult();
    }

    public function reviewsCount(): int
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.idReview)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getAverageReviewValuesByConseil()
    {
        return $this->createQueryBuilder('r')
            ->select('c.nomConseil as conseilName', 'AVG(r.value) as averageValue')
            ->leftJoin('r.idConseil', 'c')
            ->groupBy('c.idConseil')
            ->getQuery()
            ->getResult();
    }

    public function countReviewsByConseilId(int $conseilId): int
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.idReview)')
            ->andWhere('r.idConseil = :conseilId')
            ->setParameter('conseilId', $conseilId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getRatingDistribution(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.value, COUNT(r.idReview) AS reviewCount')
            ->groupBy('r.value')
            ->orderBy('r.value', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getAverageRatingByConseil(int $conseilId): ?float
    {
        $query = $this->createQueryBuilder('r')
            ->select('AVG(r.value) AS averageRating')
            ->andWhere('r.idConseil = :conseilId')
            ->setParameter('conseilId', $conseilId)
            ->getQuery();

        return $query->getSingleScalarResult();
    }
}