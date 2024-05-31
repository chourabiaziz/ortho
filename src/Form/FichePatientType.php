<?php

namespace App\Form;

use App\Entity\FichePatient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FichePatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('cin')
            ->add('telephone')
            ->add('sexe',ChoiceType::class ,[
                'choices' => [
                    'Masculin' => 'masculin',
                    'Féminin' => 'feminin',
                 ],

            ])



            ->add('ville')

            ->add('dateDeNaissance')
            
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 6
                ]
            ]);         ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FichePatient::class,
        ]);
    }
}
