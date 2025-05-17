<?php

namespace App\Form;

use App\Entity\Avis;
use App\Entity\Films;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AvisType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add ('commentaire', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
            ])
            ->add ('note', null, [
                'attr' => [
                    'min' => 0,
                    'max' => 5,
                ],
                'disabled' => true,
            ])
            ->add ('approuve', CheckboxType::class, [
                'label' => 'Approuvé',
                'required' => false,
            ])
            ->add ('film', EntityType::class, [
                'class' => Films::class,
                'choice_label' => 'titre',
                'disabled' => true,
            ]);
    }

    public function configureOptions (OptionsResolver $resolver): void
    {
        $resolver->setDefaults ([
            'data_class' => Avis::class,
        ]);
    }
}
