<?php

namespace App\Form;

use App\Entity\FichePatient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FichePatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('sexe')
            ->add('dateDeNaissance', null, [
                'widget' => 'single_text',
            ])
            ->add('dateAjout', null, [
                'widget' => 'single_text',
            ])
            ->add('description')
            ->add('ville')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FichePatient::class,
        ]);
    }
}
