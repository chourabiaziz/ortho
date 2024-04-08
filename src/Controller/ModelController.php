<?php

namespace App\Controller;

use App\Entity\Model;
use App\Entity\Abonnement;
use App\Form\ModelType;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/model')]
class ModelController extends AbstractController
{
    #[Route('/', name: 'app_model_index', methods: ['GET'])]
    public function index(ModelRepository $modelRepository): Response
    {
        return $this->render('model/index.html.twig', [
            'models' => $modelRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_model_new', methods: ['GET', 'POST'])]
    public function new(Abonnement $ab,Request $request, EntityManagerInterface $entityManager): Response
    {
        $model = new Model();
        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $model->addAbonnement($ab);
            $entityManager->persist($model);
            $entityManager->flush();

            return $this->redirectToRoute('app_model_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('model/new.html.twig', [
            'model' => $model,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_model_show', methods: ['GET'])]
    public function show(Model $model): Response
    {
        if ($this->isGranted("ROLE_ADMIN")) {

        return $this->render('model/show.html.twig', [
            'model' => $model,
        ]);}else{
            return $this->render('model/show_client.html.twig', [
                'model' => $model,
            ]);
            
        }
    }

    #[Route('/{id}/edit', name: 'app_model_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Model $model, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_model_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('model/edit.html.twig', [
            'model' => $model,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_model_delete', methods: ['POST'])]
    public function delete(Request $request, Model $model, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$model->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($model);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_model_index', [], Response::HTTP_SEE_OTHER);
    }
}
