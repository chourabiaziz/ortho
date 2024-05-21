<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ErreurController extends AbstractController
{
    #[Route('/erreur404', name: 'erreur_404')]
    public function index(): Response
    {
        return $this->redirectToRoute('erreur/index.html.twig', [], Response::HTTP_SEE_OTHER);
    }
}
