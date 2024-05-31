<?php

namespace App\Repository;

use App\Entity\Achat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Achat>
 *
 * @method Achat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Achat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Achat[]    findAll()
 * @method Achat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AchatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Achat::class);
    }


    public function findPersonnesAvecAbonnementPremiumValide(\DateTimeInterface $datenow , $search )
    {
        return $this->createQueryBuilder('a')
        ->join('a.abnonnement', 'abnonnement')
        ->join('a.personne', 'personne')
        ->join('personne.profil', 'profil')
        ->where('abnonnement.nom = :nom')
        ->andWhere(':datenow BETWEEN a.date AND a.Datefin')
        ->setParameter('nom', 'Abonnement Premium')
        ->setParameter('datenow', $datenow)
        ->andWhere('profil.emplacement LIKE :s OR profil.diplome LIKE :s OR profil.specialite LIKE :s  OR personne.nom LIKE :s  ')
        ->setParameter('s', '%' . $search . '%')
        ->getQuery()
        ->getResult();
    }
     public function findPersonnesAvecAbonnementPremiumValide2(\DateTimeInterface $datenow)
    {
        return $this->createQueryBuilder('a')
            ->join('a.abnonnement', 'abnonnement')
            ->join('a.personne', 'personne')
            ->where('abnonnement.nom = :nom')
            ->andWhere(':datenow BETWEEN a.date AND a.Datefin')
            ->setParameter('nom', 'Abonnement Premium')
            ->setParameter('datenow', $datenow)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Achat[] Returns an array of Achat objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Achat
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
