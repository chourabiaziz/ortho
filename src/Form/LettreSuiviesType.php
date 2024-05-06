<?php

namespace App\Form;

use App\Entity\LettreSuivies;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Required;

class LettreSuiviesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('ortho', EntityType::class, [
            'class' => User::class,
            'query_builder' => function (UserRepository $er) {
                return $er->createQueryBuilder('u')
                ->andWhere('u.roles LIKE :role')
                ->setParameter('role', '%ROLE_USER%');
            },
            'required' => true,  
            
        ])   ->add('nomP');
      
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LettreSuivies::class,
        ]);
    }
}
