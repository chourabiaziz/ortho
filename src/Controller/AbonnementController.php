<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Achat;
use App\Entity\Facture;
use App\Entity\Notification;
use App\Form\AbonnementType;
use App\Repository\AbonnementRepository;
use App\Repository\NotificationRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/abonnement')]
class AbonnementController extends AbstractController
{
    #[Route('/', name: 'app_abonnement_index', methods: ['GET'])]
    public function index(AbonnementRepository $abonnementRepository ,NotificationRepository $nr, EntityManagerInterface $em): Response
    {


         $achat_active = $em->getRepository(Achat::class)->createQueryBuilder('a')
        ->where('a.personne = :personne')
        ->setParameter('personne', $this->getUser())
        ->getQuery()
        ->getResult();;



         if ($this->isGranted("ROLE_ADMIN")) {
            return $this->render('abonnement/index.html.twig', [
                'abonnements' => $abonnementRepository->findAll(),
                'active'=>$achat_active,
                'notifications' => $nr->fnotif() ,

            ]);
        }
        return $this->render('abonnement/index_client.html.twig', [
            'abonnements' => $abonnementRepository->findAll(),
            'active'=>$achat_active
        ]);
    }

    #[Route('/new', name: 'app_abonnement_new', methods: ['GET', 'POST'])]
    public function new(Request $request ,NotificationRepository $nr, EntityManagerInterface $entityManager): Response
    {
        $abonnement = new Abonnement();
        $form = $this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($abonnement);


            return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('abonnement/new.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form,
            'notifications' => $nr->fnotif() ,

        ]);
    }

    #[Route('/{id}', name: 'app_abonnement_show', methods: ['GET'])]
    public function show(Abonnement $abonnement ,NotificationRepository $nr,): Response
    {
        

        
            if ($this->isGranted("ROLE_ADMIN")) {
                return $this->render('abonnement/show.html.twig', [
                    'abonnement' => $abonnement,
                    'notifications' => $nr->fnotif() ,

                ]);
            }else{
                $this->denyAccessUnlessGranted("ROLE_USER", null,"");
            return $this->render('abonnement/show_client.html.twig', [
                'abonnement' => $abonnement,
            ]);}
    }

    #[Route('/{id}/edit', name: 'app_abonnement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Abonnement $abonnement, EntityManagerInterface $entityManager ,NotificationRepository $nr,): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN", "waaa?",'Zone admin !!!!!!!!!!!!!!');

        $form = $this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('abonnement/edit.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form,
            'notifications' => $nr->fnotif() ,

        ]);
    }

    #[Route('delete/abo/{id}', name: 'app_abonnement_delete')]
    public function delete(Request $request, Abonnement $abonnement,  EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN", null,"");

        


            


            $entityManager->remove($abonnement);
            $entityManager->flush();
        

        return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
    }







 
    #[Route('/acheter/{id}', name: 'app_abonnement_acheter', methods: ['GET', 'POST'])]
    public function acheter(Request $request, Abonnement $abonnement, EntityManagerInterface $entityManager): Response
    {
        //pour render a la page login 

        $achat = new Achat;



         
        

       // $achats = $this->getUser()->getAchats();
        

       /* $alreadyPurchased = false;
        foreach ( as $chat) {
            if ($achat->getAbonnement() == $abonnement) {
                $alreadyPurchased = true;
                break;
            }
        }
*/

        $achat->setPersonne($this->getUser());
        $achat->setAbnonnement($abonnement);
        $achat->setDate(new \DateTime('now'));
        
         
        $dateActuelle = $achat->getDate();
        
        // Créer un nouvel objet DateTime à partir de la date actuelle
        $dateFin = new DateTime($dateActuelle->format('Y-m-d')); // Copie la date actuelle
        
        // Ajoutez 30 jours à la date de fin
        $dateFin->modify('+30 days');
        
        // Enregistrez la date de fin dans votre objet d'achat
        $achat->setDatefin($dateFin);
        
        $entityManager->persist($achat);

        $facture = new Facture ;
        $facture->setAbonnement($abonnement);
        $facture->setCreatedat(new \DateTime('now'));
        $facture->setReciever($this->getUser());
        $facture->setTva(19);
        $total = $abonnement->getPrix() + ( $abonnement->getPrix() * 19 / 100 ) ;
        $facture->setTotale($total);

      
        $notif = new Notification();
        $notif->setType("abonnement");
        $notif->setDescription( $this->getUser(). "  a acheté un " .$abonnement->getNom() ." de ". $abonnement->getPrix()." TND");
        $notif->setCreatedat(new DateTime('now'));
        $notif->setReaded(false);
        $entityManager->persist($notif);


         $entityManager->persist($facture);
        $entityManager->flush();
      

            return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
      

    }









}
