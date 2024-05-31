<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NotificationController extends AbstractController
{
    #[Route('/notification', name: 'app_notification')]
    public function index(NotificationRepository $nr ,EntityManagerInterface $em): Response
    {                $user = $this->getUser();


        if ($this->isGranted('ROLE_ADMIN')) {
            

            foreach ($nr->findAll() as $not) {
                if ($not->getType() == "abonnement") {
                    $not->setReaded(true);
                    $em->persist($not);
                    $em->flush();
                }
            }


            return $this->render('notification/index.html.twig', [
                'notifications' => $nr->fnotif() ,
            ]);
         } else {

           




            return $this->render('notification/index_client.html.twig', [
                'notifications' => $nr->usernotif($user) 
            ]);


      
        }}

        #[Route('/notification/readed', name: 'app_notification_readed')]
        public function read(NotificationRepository $nr , EntityManagerInterface $em): Response
        {                $user = $this->getUser();
    
    
             
            $all = $nr->findAll();
            
            foreach ($all as $x) {
                if ($x->getReciever($this->getUser())) {
                    $x->setReaded(true);
                    $em->persist($x);

                }
               $em->flush();
            }
            return $this->redirectToRoute('app_notification', [], Response::HTTP_SEE_OTHER);

    
    
    
 
    
          
            }
       
}
