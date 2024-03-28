<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */

        /**
     * @Route("/check-role", name="check_role")
     */
    public function checkUserRole()
    {
        // Vérifiez si l'utilisateur est authentifié
        if ($this->security->isGranted('ROLE_ADMIN')) {
            // L'utilisateur a le rôle d'administrateur
            // Faites quelque chose pour les administrateurs
        } elseif ($this->security->isGranted('ROLE_ORTHOPHONISTE')) {
            // L'utilisateur a le rôle d'orthophoniste
            // Faites quelque chose pour les orthophonistes
        } elseif ($this->security->isGranted('ROLE_SECRETAIRE')) {
            // L'utilisateur a le rôle de secrétaire
            // Faites quelque chose pour les secrétaires
        } else {
            // L'utilisateur n'a pas de rôle approprié
            // Faites quelque chose dans ce cas
        }

        // Vous pouvez retourner une réponse ou rediriger vers une autre page selon vos besoins
    }
}
