<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Professeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



class ClasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('libelle',TextType::class,[
            'required'=> false
        ])
        ->add('filiere',ChoiceType::class,[
           'choices'=>Classe::$filieres,
           'required'=> false
        ])
        ->add('niveau',ChoiceType::class,[
            'choices'=>Classe::$niveaux,
            'required'=> false
        ])
        // ->add('professeurs',EntityType::class,[
        //     'class'=>Professeur::class,
        //     'multiple'=>true,
        //     'choice_label'=>'nomComplet',
        //     'attr'=>[
        //         'class'=>'select selectpicker',
        //         'data-live-search'=>true
        //     ]
        // ])
        ->add('professeurs',EntityType::class,[
            'class'=>Professeur::class,
            'multiple'=>true,
            'attr'=>[
                'class'=>'select selectpicker',
                'data-live-search'=>true
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Classe::class,
        ]);
    }
}
