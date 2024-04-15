<?php

namespace App\Form;

use App\Entity\Abonnement;
use App\Entity\Facture;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tva')
            ->add('totale')
            ->add('createdat', null, [
                'widget' => 'single_text'
            ])
            ->add('createdby', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
            ->add('reciever', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
            ->add('abonnement', EntityType::class, [
                'class' => Abonnement::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
