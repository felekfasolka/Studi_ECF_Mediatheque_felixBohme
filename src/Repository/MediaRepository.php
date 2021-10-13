<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function countNotPicked(): int
    {
        return $this->getOldReservationQueryBuilder()->select('COUNT(c.id)')->getQuery()->getSingleScalarResult();
    }

    public function deleteNotPicked()
    {
        // return $this->getOldReservationQueryBuilder()->delete()->getQuery()->execute();
    }

    private function getOldReservationQueryBuilder(): QueryBuilder
    {
            return $this->createQueryBuilder('c')
                ->andWhere('c.isBorrowedAt < :date')
                ->setParameter('date', new \DateTime('-3 day'));
    }

    /**
     * @return Book[] Returns an array of Book objects
     */

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.isBorrowedBy = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

}
