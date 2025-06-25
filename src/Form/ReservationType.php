<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Siege;
use App\Enum\ReservationStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class ReservationType extends AbstractType
{
    /**
     * Construit le formulaire de réservation.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombrePlace', IntegerType::class, [
                'label' => 'Nombre de places',
                'attr' => ['min' => 1, 'max' => 10],
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner le nombre de places.']),
                    new Range([
                        'min' => 1,
                        'max' => 10,
                        'notInRangeMessage' => 'Le nombre de places doit être compris entre {{ min }} et {{ max }}.',
                    ]),
                ],
            ])
            // Choir le type de siège
            ->add('sieges', EntityType::class, [
                'class' => Siege::class,
                'choice_label' => fn($siege) => $siege->getNumero(),
                'label' => 'Type de siège',
                'expanded' => true,
                'multiple' => true,
            ])
            // affiche l'utilisateur connecté et griser le champ
            ->add('user', EntityType::class, [
                'class' => 'App\Entity\User',
                'choice_label' => 'id',
                'label' => 'Utilisateur',
                'disabled' => true,
                'mapped' => false,
            ])
            ->add('seance', EntityType::class, [
                'class' => 'App\Entity\Seance',
                'choice_label' => 'id',
                'label' => 'Séance',
                // 'disabled' => true,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => ReservationStatus::cases(),
                'choice_label' => fn(ReservationStatus $status) => $status->name,
                'choice_value' => fn(?ReservationStatus $status) => $status?->value,
                'label' => 'Statut',
                //'disabled' => true,
            ]);
    }

    /**
     * Configure les options du formulaire.
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
