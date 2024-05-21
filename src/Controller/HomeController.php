<?php

namespace App\Controller;

use App\Repository\AchatRepository;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(NotificationRepository $nr , AchatRepository $ur): Response
    {

        if ($this->isGranted('ROLE_ADMIN')) {

            return $this->render('home/index.html.twig', [
                'notifications' => $nr->fnotif() ,
            ]);

         } else {
            return $this->render('home/index_client.html.twig', [
                'users' => $ur->findPersonnesAvecAbonnementPremiumValide(new \DateTime('now')),
            ]);
         }


      



    }
}
