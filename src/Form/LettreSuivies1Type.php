<?php

namespace App\Form;

use App\Entity\LettreSuivies;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
 
class LettreSuivies1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       
    }           
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LettreSuivies::class,
        ]);
    }
}
