<?php

namespace App\Controller;

use App\Entity\LettreSuivies;
use App\Form\LettreSuiviesType;
use App\Repository\LettreSuiviesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/lettre')]
class LettreSuiviesController extends AbstractController
{
    #[Route('/', name: 'app_lettre_suivies_index', methods: ['GET'])]
    public function index(LettreSuiviesRepository $lettreSuiviesRepository): Response
    {
        return $this->render('lettre_suivies/index.html.twig', [
            'lettre_suivies' => $lettreSuiviesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_lettre_suivies_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lettreSuivy = new LettreSuivies();
        $form = $this->createForm(LettreSuiviesType::class, $lettreSuivy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lettreSuivy);
            $entityManager->flush();

            return $this->redirectToRoute('app_lettre_suivies_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lettre_suivies/new.html.twig', [
            'lettre_suivy' => $lettreSuivy,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lettre_suivies_show', methods: ['GET'])]
    public function show(LettreSuivies $lettreSuivy): Response
    {
        return $this->render('lettre_suivies/show.html.twig', [
            'lettre_suivy' => $lettreSuivy,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lettre_suivies_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LettreSuivies $lettreSuivy, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LettreSuiviesType::class, $lettreSuivy);
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
