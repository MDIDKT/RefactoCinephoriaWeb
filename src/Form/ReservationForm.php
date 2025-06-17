<?php

namespace App\Form;

use App\Entity\Cinema;
use App\Entity\Film;
use App\Entity\Reservation;
use App\Entity\Salle;
use App\Entity\Seance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombrePlace')
            ->add('date', DateTimeType::class)
            ->add('status')
            ->add('QRCode')
            ->add('cinema', EntityType::class, [
                'class' => Cinema::class,
                'choice_label' => 'id',
            ])
            ->add('salle', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => 'id',
            ])
            ->add('film', EntityType::class, [
                'class' => Film::class,
                'choice_label' => 'id',
            ])
            ->add('seance', EntityType::class, [
                'class' => Seance::class,
                'choice_label' => 'id',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
