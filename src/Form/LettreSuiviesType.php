<?php

namespace App\Form;

use App\Entity\LettreSuivies;
use App\Entity\User;
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
        ->add('ortho')
        ->add('nomP')
        ->add('description', TextareaType::class, [
            'attr' => [
                'class' => 'form-control',
                'rows' => 50 ,
                 
            ]
        ]); 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LettreSuivies::class,
        ]);
    }
}
