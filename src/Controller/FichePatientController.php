<?php

namespace App\Controller;

use App\Entity\FichePatient;
use App\Form\FichePatientType;
use App\Repository\FichePatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Date;

#[Route('/fichepatient')]
class FichePatientController extends AbstractController
{
    #[Route('/', name: 'app_fiche_patient_index', methods: ['GET'])]
    public function index(FichePatientRepository $fichePatientRepository): Response
    {
        return $this->render('fiche_patient/index.html.twig', [
            'fiche_patients' => $fichePatientRepository->findAll(),
        ]);
    }






    #[Route('/new', name: 'app_fiche_patient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        $now = new \DateTimeImmutable('now');


        $fichePatient = new FichePatient();
        $form = $this->createForm(FichePatientType::class, $fichePatient);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            
            $fichePatient->setDateAjout($now);
          
            $entityManager->persist($fichePatient);
            $entityManager->flush();
             
            return $this->redirectToRoute('app_fiche_patient_index');
        }

        return $this->render('fiche_patient/new.html.twig', [
            'fiche_patient' => $fichePatient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_patient_show', methods: ['GET'])]
    public function show(FichePatient $fichePatient): Response
    {
        return $this->render('fiche_patient/show.html.twig', [
            'fiche_patient' => $fichePatient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fiche_patient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FichePatient $fichePatient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FichePatientType::class, $fichePatient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fiche_patient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fiche_patient/edit.html.twig', [
            'fiche_patient' => $fichePatient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_patient_delete', methods: ['POST'])]
    public function delete(Request $request, FichePatient $fichePatient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fichePatient->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($fichePatient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fiche_patient_index', [], Response::HTTP_SEE_OTHER);
    }
}
