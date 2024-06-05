<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\LettreSuivies;
use App\Entity\Notification;
use App\Form\CommentType;
use App\Form\LettreSuivies1Type;
use App\Form\LettreSuiviesType;
use App\Form\LettreType;
use App\Repository\CommentRepository;
use App\Repository\LettreSuiviesRepository;
use App\Repository\NotificationRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/lettre')]
class LettreSuiviesController extends AbstractController
{
    #[Route('/', name: 'app_lettre_suivies_index', methods: ['GET'])]
    public function index(LettreSuiviesRepository $lettreSuiviesRepository, NotificationRepository $nr , Request $request ,PaginatorInterface $paginatorInterface): Response
    {
        $pagination = $paginatorInterface->paginate(

            $lettreSuiviesRepository->findalldesc($this->getUser()),
            $request->query->get('page', 1),
            3 //number of element per page 
        );
       
            return $this->render('lettre_suivies/index_client.html.twig', [
                'lettre_suivies' => $pagination,
            ]);
        
    }

    #[Route('/new', name: 'app_lettre_suivies_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ls = new LettreSuivies();
        $form = $this->createForm(LettreSuivies1Type::class, $ls);
        $form->handleRequest($request);
        $modele = $request->query->get('modele');
        if ($modele) {
            $ls->setNimage($modele);
            $entityManager->persist($ls);
            $entityManager->flush();



            return $this->redirectToRoute('app_lettre_suivies_new_lettre', ['id' => $ls->getId()], Response::HTTP_SEE_OTHER);
        }



        return $this->render('lettre_suivies/new_client.html.twig', [
            'lettre_suivy' => $ls,
            'form' => $form,

        ]);
    }

    #[Route('/new/etape2/{id}/new', name: 'app_lettre_suivies_new_lettre', methods: ['GET', 'POST'])]
    public function etape_2(LettreSuivies $ls, Request $request, EntityManagerInterface $entityManager): Response
    {

        $currentUser = $this->getUser();

        $form = $this->createForm(LettreSuiviesType::class, $ls, [
            'current_user' => $currentUser,
        ]);

         $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $modele = $ls->getNimage();
            $ls->setDate(new DateTime('now'));

            if ($modele == 1) {
                $ls->setCreatedby($this->getUser());
                $ls->setNimage($modele);
                $ls->setType("d'Affectation");
                $entityManager->persist($ls);
                $entityManager->flush();

                return $this->redirectToRoute('app_lettre_suivies_new_etape2_suivie', ['id' => $ls->getId(),]);
            } else if ($modele == 2) {
                $ls->setCreatedby($this->getUser());
                $ls->setNimage($modele);
                $ls->setType("coordination");
                $entityManager->persist($ls);
                $entityManager->flush();

                return $this->redirectToRoute('app_lettre_suivies_new_etape2_coordination', ['id' => $ls->getId(),]);
            }
            else if ($modele == 3) {
                $ls->setCreatedby($this->getUser());
                $ls->setNimage($modele);
                $ls->setType("remerciment");
                $entityManager->persist($ls);
                $entityManager->flush();

                return $this->redirectToRoute('app_lettre_suivies_new_etape2_remerciment', ['id' => $ls->getId(),]);
            }
            else if ($modele == 4) {
                $ls->setCreatedby($this->getUser());
                $ls->setNimage($modele);
                $ls->setType("rapport");
                $entityManager->persist($ls);
                $entityManager->flush();

                return $this->redirectToRoute('app_lettre_suivies_new_etape2_rapport', ['id' => $ls->getId(),]);
            }
            else if ($modele == 5) {
                $ls->setCreatedby($this->getUser());
                $ls->setNimage($modele);
                $ls->setType("suivie");
                $entityManager->persist($ls);
                $entityManager->flush();

                return $this->redirectToRoute('app_lettre_suivies_new_etape2_sss', ['id' => $ls->getId(),]);
            }
        }
        return $this->render('lettre_suivies/new_client2.html.twig', [
            'lettre_suivy' => $ls,
            'form' => $form,

        ]);
    }


    #[Route('/new/etape2/{id}/suivie', name: 'app_lettre_suivies_new_etape2_suivie', methods: ['GET', 'POST'])]
    public function etape2_suivie(LettreSuivies $ls, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LettreType::class, $ls);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $notif = new Notification();
            $notif->setType("Lettre affectation reçu");
            $notif->setReciever($ls->getOrtho());
            $notif->setDescription( $this->getUser(). "  vous a envoyer  une lettre de affectation");
            $notif->setCreatedat(new DateTime('now'));
            $notif->setReaded(false);
            $entityManager->persist($notif);
            $entityManager->persist($ls);
            $entityManager->flush();

            return $this->redirectToRoute('app_lettre_suivies_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('lettre_suivies/lettres/affectation.html.twig', [
            'lettre' => $ls,
            'form' => $form,

        ]);
    }

    #[Route('/new/etape2/{id}/coordination', name: 'app_lettre_suivies_new_etape2_coordination', methods: ['GET', 'POST'])]
    public function etape2_cordination(LettreSuivies $ls, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LettreType::class, $ls);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $notif = new Notification();
            $notif->setType("Lettre coordination reçu");
            $notif->setDescription( $this->getUser(). "  vous a envoyer  une lettre de coordination");
            $notif->setCreatedat(new DateTime('now'));
            $notif->setReaded(false);
            $notif->setReciever($ls->getOrtho());

            $entityManager->persist($notif);
            $entityManager->persist($ls);
            $entityManager->flush();

            return $this->redirectToRoute('app_lettre_suivies_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('lettre_suivies/lettres/coordination.html.twig', [
            'lettre' => $ls,
            'form' => $form,

        ]);
    }









    #[Route('/new/etape2/{id}/remerciment', name: 'app_lettre_suivies_new_etape2_remerciment', methods: ['GET', 'POST'])]
    public function etape2_remerciment(LettreSuivies $ls, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LettreType::class, $ls);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $notif = new Notification();
            $notif->setType("Lettre remerciment reçu");
            $notif->setDescription( $this->getUser(). "  vous a envoyer  une lettre de remerciment");
            $notif->setCreatedat(new DateTime('now'));
            $notif->setReaded(false);
            $notif->setReciever($ls->getOrtho());

            $entityManager->persist($notif);
            $entityManager->persist($ls);
            $entityManager->flush();

            return $this->redirectToRoute('app_lettre_suivies_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('lettre_suivies/lettres/remerciment.html.twig', [
            'lettre' => $ls,
            'form' => $form,

        ]);
    }
    #[Route('/new/etape2/{id}/rapport', name: 'app_lettre_suivies_new_etape2_rapport', methods: ['GET', 'POST'])]
    public function etape2_rapport(LettreSuivies $ls, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LettreType::class, $ls);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $notif = new Notification();
            $notif->setType("Lettre rapport reçu");
            $notif->setDescription( $this->getUser(). "  vous a envoyer  une lettre de rapport");
            $notif->setCreatedat(new DateTime('now'));
            $notif->setReaded(false);
            $notif->setReciever($ls->getOrtho());

            $entityManager->persist($notif);
            $entityManager->persist($ls);
            $entityManager->flush();

            return $this->redirectToRoute('app_lettre_suivies_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('lettre_suivies/lettres/rapport.html.twig', [
            'lettre' => $ls,
            'form' => $form,

        ]);
    }
    #[Route('/new/etape2/{id}/suivieeeeee', name: 'app_lettre_suivies_new_etape2_sss', methods: ['GET', 'POST'])]
    public function etape2_sss(LettreSuivies $ls, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LettreType::class, $ls);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $notif = new Notification();
            $notif->setType("Lettre suivie reçu");
            $notif->setDescription( $this->getUser(). "  vous a envoyer  une lettre de suivie");
            $notif->setCreatedat(new DateTime('now'));
            $notif->setReaded(false);
            $notif->setReciever($ls->getOrtho());

            $entityManager->persist($notif);
            $entityManager->persist($ls);
            $entityManager->flush();

            return $this->redirectToRoute('app_lettre_suivies_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('lettre_suivies/lettres/suivie.html.twig', [
            'lettre' => $ls,
            'form' => $form,

        ]);
    }











    #[Route('/{id}', name: 'app_lettre_suivies_show')]
    public function show(LettreSuivies $lettreSuivy, Request $request, EntityManagerInterface $entityManager, CommentRepository $cr): Response
    {
        $comment = new Comment();
        $formulaire = $this->createForm(CommentType::class, $comment);
        $formulaire->handleRequest($request);

        $submitted = $request->query->get('submitted');
        $id_comment = $request->query->get('comment');
        $content = $request->query->get('description');
        if ($request->query->get('submitted') == 1) {
            $comment->setSender($this->getUser());
            $comment->setDescription($content);
            $comment->setCreatedat(new DateTime('now'));
            $comment->setLettre($lettreSuivy);
            $comment->setParent($cr->find((int) $id_comment));
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('app_lettre_suivies_show', ['id' => $lettreSuivy->getId()], Response::HTTP_SEE_OTHER);
        }

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $comment->setSender($this->getUser());
            $comment->setCreatedat(new DateTime('now'));
            $comment->setLettre($lettreSuivy);
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('app_lettre_suivies_show', ['id' => $lettreSuivy->getId()], Response::HTTP_SEE_OTHER);
        }
        $query = $entityManager->createQueryBuilder()
            ->select('c')
            ->from(Comment::class, 'c') // Utilisation de l'alias 'c' pour l'entité Comment
            ->where('c.lettre = :lettre_suivi')
            ->setParameter('lettre_suivi', $lettreSuivy);

        $result = $query->getQuery()->getResult();
        return $this->render('lettre_suivies/show_client.html.twig', [
            'lettre' => $lettreSuivy,
            'formulaire' => $formulaire,
            'comments' => $result

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

    #[Route('/{id}/delete/lettre', name: 'app_lettre_suivies_delete')]
    public function delete(Request $request, LettreSuivies $lettreSuivy, EntityManagerInterface $entityManager): Response
    {
             $entityManager->remove($lettreSuivy);
            $entityManager->flush();
        

        return $this->redirectToRoute('app_lettre_suivies_index', [], Response::HTTP_SEE_OTHER);
    }
}
