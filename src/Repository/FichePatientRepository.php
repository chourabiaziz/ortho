<?php

namespace App\Repository;

use App\Entity\FichePatient;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FichePatient>
 *
 * @method FichePatient|null find($id, $lockMode = null, $lockVersion = null)
 * @method FichePatient|null findOneBy(array $criteria, array $orderBy = null)
 * @method FichePatient[]    findAll()
 * @method FichePatient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichePatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FichePatient::class);
    }

//    /**
//     * @return FichePatient[] Returns an array of FichePatient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FichePatient
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }



public function findalldesc($user): array
{
    return $this->createQueryBuilder('f')
        ->where('f.createdby = :user')
        ->orderBy('f.id', 'DESC')
        ->setParameter('user', $user)
        ->getQuery()
        ->getResult();
}
public function search($s , $user)
{
    $query = $this->createQueryBuilder('p')
    ->where('p.createdby = :user')
    ->orderBy('p.id', 'DESC')
    ->setParameter('user', $user)
    ->andWhere('p.cin LIKE :s OR p.ville LIKE :s OR p.ville LIKE :s OR p.prenom LIKE :s OR p.nom LIKE :s')
    ->setParameter('s', '%' . $s . '%');
    
   
    return $query->getQuery()->getResult();
}

}
