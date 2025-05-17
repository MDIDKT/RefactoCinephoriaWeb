<?php

namespace App\Form;

use App\Entity\Cinemas;
use App\Entity\Films;
use App\Entity\Salles;
use App\Entity\Seance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeanceType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add ('HeureDebut', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add ('films', EntityType::class, [
                'class' => Films::class,
                'choice_label' => 'titre',
            ])
            ->add ('salle', EntityType::class, [
                'class' => Salles::class,
                'choice_label' => 'numeroSalle',
                'placeholder' => 'Choisissez une salle',
                'required' => true, // Rendre obligatoire
            ])
            ->add ('cinemas', EntityType::class, [
                'class' => Cinemas::class,
                'choice_label' => 'nom'
            ])
            ->add('qualite', ChoiceType::class, [
                'choices' => [
                    '4K' => '4K',
                    '3D' => '3D',
                ],
                'placeholder' => 'Choisissez une qualité', // Guide l'utilisateur
                'required' => true,
            ]);

    }

    public function configureOptions (OptionsResolver $resolver): void
    {
        $resolver->setDefaults ([
            'data_class' => Seance::class,
        ]);
    }
}
