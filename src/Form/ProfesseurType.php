<?php

namespace App\Form;

use App\Entity\Professeur;
use App\Entity\Classe;
use App\Entity\Module;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ProfesseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nomComplet',TextType::class,[
            'required'=> false
        ])
        ->add('grade',TextType::class,[
            'required'=> false

        ])
        ->add('sexe', ChoiceType::class, [
           'choices'  => [
               'Masculin' => 'masculin',
               'Feminin' => 'feminin'
           ]])
           ->add('classes',EntityType::class,[
            'class' => Classe::class,
            'multiple'=>true,
            'choice_label' => 'libelle',
            'attr'=>[
                'class'=>'select selectpicker',
                'data-live-search'=>true,
                'placeholder'=>'les modules disponibles',
            ]
        ])
        ->add('modules',EntityType::class,[
            'class' => Module::class,
            'multiple'=>true,
            'choice_label' => 'libelle',
            'attr'=>[
                'class'=>'select selectpicker',
                'data-live-search'=>true,
                'placeholder'=>'les modules disponibles',
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Professeur::class,
        ]);
    }
}
