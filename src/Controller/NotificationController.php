<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NotificationController extends AbstractController
{
    #[Route('/notification', name: 'app_notification')]
    public function index(NotificationRepository $nr): Response
    {

        if ($this->isGranted('ROLE_ADMIN')) {

            return $this->render('notification/index.html.twig', [
                'notifications' => $nr->fnotif() ,
            ]);
         } else {
            return $this->render('notification/index_client.html.twig', [


                'notifications' => $nr->fnotif() ,
            ]);


      
        }}
}
