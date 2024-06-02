<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
  use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface; // Assurez-vous d'importer cette classe
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository ,Request $request ,NotificationRepository $nr,): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN", null,"");


       $search=  $request->get('search');
       $searched =   $userRepository->search($search);

            if($search) {
                 $users = $userRepository->search($search);
            } else {
                $users = $userRepository->fnotif();
            }



        return $this->render('user/index.html.twig', [
            'users' => $users,
            'notifications' => $nr->fnotif() ,

            
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request,UserPasswordHasherInterface $userPasswordHasher ,EntityManagerInterface $entityManager ,NotificationRepository $nr,): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN", null,"");

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()    ) {

            $user->setRoles(["ROLE_ADMIN"]);
            $imageFile = $form->get('image')->getData();
            $user->setTest($user->getPassword());
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '.' . $imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image.');
                    return $this->redirectToRoute('app_student_group_index_administrateur');
                }
    
                $user->setImage($newFilename);
            } else {
                $user->setImage("default.png");

            }
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'notifications' => $nr->fnotif() ,

        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user ,NotificationRepository $nr,): Response
    {
        if ($this->isGranted("ROLE_ADMIN")) {
            return $this->render('user/show.html.twig', [
                'user' => $user,
                'notifications' => $nr->fnotif() ,
            ]);
    
        }else{

            return $this->render('user/show_client.html.twig', [
                'user' => $user,   
            ]);
         }


       


    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserPasswordHasherInterface $userPasswordHasher, User $user, EntityManagerInterface $entityManager ,NotificationRepository $nr,): Response
    {
        $msg="";
          $form = $this->createForm(UserType::class, $user);
         $form->handleRequest($request);
        

         if ($form->isSubmitted() && $form->isValid() && $request->get('code')==$user->getTest()) {
            
            $imageFile = $form->get('image')->getData();
     
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '.' . $imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image.');
                    return $this->redirectToRoute('app_student_group_index_administrateur');
                }
    
                $user->setImage($newFilename);
            } else {
                $user->setImage("default.png");

            }
        
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_user_show',['id'=>$user->getId()]);
            
           

      



        }
        else {
            $msg="shitt";
        }
        if ($this->isGranted("ROLE_ADMIN")) {
            return $this->render('user/edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
                'notifications' => $nr->fnotif() ,
                'msg'=>$msg
 
            ]);
        }else{
            return $this->render('user/edit_client.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
                'msg'=>$msg

            ]);
      
        }  

       
    }
    private function verifyPassword($code, UserPasswordHasherInterface $userPasswordHasher, PasswordAuthenticatedUserInterface $user): bool
{
    return $userPasswordHasher->isPasswordValid($user, $code);
}

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN", null,"");

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }











    #[Route('/{id}/accepte', name: 'app_user_accepte')]
    public function accepte(Request $request, User $user,MailerInterface $mailer ,EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN", null,"");


            $user->setAccepted(true);
             $entityManager->persist($user);
            $entityManager->flush();



            $email = (new TemplatedEmail())
            ->from(new Address('Admin@Orthophoniste.tn', 'ADMIN'))

            ->to($user->getEmail())
            ->subject('Demande de compt Accepté')
            ->htmlTemplate('emails/email_accepte_compt.html.twig') 
            ->context([
                
             ]);
        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
           
        }    










        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/refus', name: 'app_user_refue')]
    public function refus(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN", null,"");


            $user->setAccepted(false);
             $entityManager->persist($user);
            $entityManager->flush();
   

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }







 

}