<?php

namespace App\Controller;

use App\Entity\LettreSuivies;
use App\Form\LettreSuivies1Type;
use App\Form\LettreSuiviesType;
use App\Form\LettreType;
use App\Repository\LettreSuiviesRepository;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/lettre')]
class LettreSuiviesController extends AbstractController
{
    #[Route('/', name: 'app_lettre_suivies_index', methods: ['GET'])]
    public function index(LettreSuiviesRepository $lettreSuiviesRepository,NotificationRepository $nr): Response
    {

        if ($this->isGranted('ROLE_ADMIN')) {

            return $this->render('lettre_suivies/index.html.twig', [
                'lettre_suivies' => $lettreSuiviesRepository->findAll(),
                'notifications' => $nr->fnotif() ,

            ]);

         } else {
            return $this->render('lettre_suivies/index_client.html.twig', [
                'lettre_suivies' => $lettreSuiviesRepository->findAll(),
            ]);
         }

       
    }

    #[Route('/new', name: 'app_lettre_suivies_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ls = new LettreSuivies();
        $form = $this->createForm(LettreSuivies1Type::class, $ls);
        $form->handleRequest($request); 
        $modele = $request->query->get('modele');
        if ($modele ) {
        $ls->setNimage($modele);
        $entityManager->persist($ls);
        $entityManager->flush();

             
                
                return $this->redirectToRoute('app_lettre_suivies_new_lettre', ['id'=>$ls->getId()], Response::HTTP_SEE_OTHER);


        }
      

        if ($this->isGranted('ROLE_ADMIN')) {

            return $this->render('lettre_suivies/new.html.twig', [
                'lettre_suivy' => $ls,
                'form' => $form,
            ]);

         } else {
            return $this->render('lettre_suivies/new_client.html.twig', [
                'lettre_suivy' => $ls,
                'form' => $form,
                
            ]);
         }
       
    }

    #[Route('/new/etape2/{id}/new', name: 'app_lettre_suivies_new_lettre', methods: ['GET', 'POST'])]
    public function etape_2(  LettreSuivies $ls , Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LettreSuiviesType::class, $ls);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $modele = $ls->getNimage();
            $ls->setDate(new \DateTime('now'));

            if (  $modele == 1    ) {
                $ls->setCreatedby($this->getUser());
                $ls->setNimage($modele);
                $ls->setType("d'Affectation");
                $entityManager->persist($ls);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_lettre_suivies_new_etape2_suivie', ['id' => $ls->getId(),  ]);

            } else if($modele == 2) {
                $ls->setCreatedby($this->getUser());
                $ls->setNimage($modele);
                $ls->setType("coordination");
                $entityManager->persist($ls);
                 $entityManager->flush();
    
                return $this->redirectToRoute('app_lettre_suivies_new_etape2_coordination', ['id' => $ls->getId(),  ]);
                         }
        }
        return $this->render('lettre_suivies/new_client2.html.twig', [
            'lettre_suivy' => $ls,
            'form' => $form,
            
        ]);
    }


    #[Route('/new/etape2/{id}/suivie', name: 'app_lettre_suivies_new_etape2_suivie', methods: ['GET', 'POST'])]
    public function etape2_suivie(  LettreSuivies $ls , Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LettreType::class, $ls);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($ls);
            $entityManager->flush();

            return $this->redirectToRoute('app_lettre_suivies_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('lettre_suivies/lettres/suivie.html.twig', [
            'lettre' => $ls,
            'form' => $form,
            
        ]);
    }

    #[Route('/new/etape2/{id}/coordination', name: 'app_lettre_suivies_new_etape2_coordination', methods: ['GET', 'POST'])]
    public function etape2_cordination(  LettreSuivies $ls , Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LettreType::class, $ls);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($ls);
            $entityManager->flush();

            return $this->redirectToRoute('app_lettre_suivies_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('lettre_suivies/lettres/coordination.html.twig', [
            'lettre' => $ls,
            'form' => $form,
            
        ]);
    }

    #[Route('/{id}', name: 'app_lettre_suivies_show', methods: ['GET'])]
    public function show(LettreSuivies $lettreSuivy): Response
    {


        if ($this->isGranted('ROLE_ADMIN')) {

            return $this->render('lettre_suivies/show.html.twig', [
                'lettre' => $lettreSuivy,
            ]);

         } else {
            return $this->render('lettre_suivies/show_client.html.twig', [
                'lettre' => $lettreSuivy,
            ]);
         }



    
 
    }

    #[Route('/{id}/edit', name: 'app_lettre_suivies_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LettreSuivies $lettreSuivy, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LettreSuivies1Type::class, $lettreSuivy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_lettre_suivies_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lettre_suivies/edit.html.twig', [
            'lettre_suivy' => $lettreSuivy,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lettre_suivies_delete', methods: ['POST'])]
    public function delete(Request $request, LettreSuivies $lettreSuivy, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lettreSuivy->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($lettreSuivy);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_lettre_suivies_index', [], Response::HTTP_SEE_OTHER);
    }
}
