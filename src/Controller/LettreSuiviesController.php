<?php

namespace App\Controller;

use App\Entity\LettreSuivies;
use App\Form\LettreSuivies1Type;
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
        $ls = new LettreSuivies();
        $form = $this->createForm(LettreSuivies1Type::class, $ls);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $ls->setCreatedby($this->getUser());
            $ls->setDate(new \DateTime('now'));

            $entityManager->persist($ls);
            $entityManager->flush();

            return $this->redirectToRoute('app_lettre_suivies_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lettre_suivies/new.html.twig', [
            'lettre_suivy' => $ls,
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
