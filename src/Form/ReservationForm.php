<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Seance;
use App\Enum\ReservationStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('film', EntityType::class, [
                'class' => Seance::class,
                'choice_label' => 'film.titre',
                'label' => 'Film',
                'placeholder' => 'Sélectionnez un film',
                'mapped' => false, // important
            ])
            ->add('cinema', EntityType::class, [
                'class' => Seance::class,
                'choice_label' => 'cinema',
                'label' => 'Cinéma',
                'placeholder' => 'Sélectionnez un cinéma',
                'mapped' => false, // important
            ])
            ->add('seance', EntityType::class, [
                'class' => Seance::class,
                'choice_label' => 'id',
                // le titre du film est affiché dans la liste déroulante
                'label' => 'Séance',
                'placeholder' => 'Sélectionnez une séance',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => ReservationStatus::cases(),
                'choice_label' => fn(ReservationStatus $status) => $status->name,
                'choice_value' => fn(?ReservationStatus $status) => $status?->value,
                'label' => 'Statut',
            ])
            ->add('nombrePlace');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
