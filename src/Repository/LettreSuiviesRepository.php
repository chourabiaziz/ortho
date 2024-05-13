<?php

namespace App\Repository;

use App\Entity\LettreSuivies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LettreSuivies>
 *
 * @method LettreSuivies|null find($id, $lockMode = null, $lockVersion = null)
 * @method LettreSuivies|null findOneBy(array $criteria, array $orderBy = null)
 * @method LettreSuivies[]    findAll()
 * @method LettreSuivies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LettreSuiviesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LettreSuivies::class);
    }

    public function findalldesc(): array
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    //    public function findOneBySomeField($value): ?LettreSuivies
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
