<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Etudiant;
use App\Entity\Inscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('classe',EntityType::class,[
                'class' => Classe::class,
                'choice_label' => 'libelle'
            ])
            ->add('etudiant',CollectionType::class,[
                'entry_type' => EtudiantType::class,
                'entry_options' => ['label' => false],
            ])
            
        ;
        $builder->add('etudiant', EtudiantType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inscription::class,
        ]);
    }
}
