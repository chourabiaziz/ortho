<?php

namespace App\Controller;

use App\Repository\AchatRepository;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(NotificationRepository $nr ,EntityManagerInterface $em , UserRepository $users ,AchatRepository $ur ,Request $request): Response
    {

        if ($this->isGranted('ROLE_ADMIN')) {
            

            $nbclient = 0;
            $tot = $users->findAll();
            foreach ($tot as $u) {
                if ($u->getMatricule()) {
                    $nbclient += 1;
                }
            }

            $qb = $em->createQueryBuilder();
            $qb->select('SUM(i.totale) as totalAmount')
             ->from('App\Entity\Facture', 'i');

$total = $qb->getQuery()->getSingleScalarResult();
           

 


///////////////////
$qb = $em->createQueryBuilder();
$qb->select('u.nom as username', 'SUM(i.totale) as tt')
    ->from('App\Entity\Facture', 'i')
    ->join('i.reciever', 'u')
    ->groupBy('u.id')
    ->setMaxResults(8);

$results = $qb->getQuery()->getResult();




$uu = [];
$totalAmounts = [];
foreach ($results as $result) {
    $totalAmounts[] = $result['tt'];
    $uu[] = $result['username'];
}

 

$jsonData1 = json_encode($uu);
$jsonData2 = json_encode($totalAmounts);


            return $this->render('home/index.html.twig', [
                'notifications' => $nr->fnotif() ,
                'nbclient'=>$nbclient  ,
                'totale'=>$total  ,
                'table1'=>$jsonData1  ,
                'table2'=>$jsonData2  ,
                'users'=>$tot
                ,
                
                
            ]);

         } else {







            $search=  $request->get('search');
            if($search) {

                $users =   $ur->findPersonnesAvecAbonnementPremiumValide(new \DateTime('now') , $search);

            } else {

                $users =   $ur->findPersonnesAvecAbonnementPremiumValide2(new \DateTime('now'));


            }




            return $this->render('home/index_client.html.twig', [

                
                'users' => $users


            ]);
         }


      



    }
}
