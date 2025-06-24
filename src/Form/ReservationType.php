<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Siege;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use App\Enum\ReservationStatus;

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
            ->add('sieges', EntityType::class, [
                'class' => Siege::class,
                'choice_label' => function (Siege $siege) {
                    return $siege->getNumero();
                },
                'multiple' => true,
                'expanded' => true,
                'label' => 'Sélectionnez vos sièges (PMR inclus si besoin)',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Merci de sélectionner au moins un siège.']),
                ],
                'attr' => [
                    'aria-label' => 'Sélection des sièges, y compris PMR',
                ],
            ])
            ->add('user', EntityType::class, [
                'class' => 'App\Entity\User',
                'choice_label' => 'nom',
                'label' => 'Utilisateur',
                'disabled' => true,
            ])
            ->add('seance', EntityType::class, [
                'class' => 'App\Entity\Seance',
                'choice_label' => 'id',
                'label' => 'Séance',
                'disabled' => true,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => ReservationStatus::cases(),
                'choice_label' => fn(ReservationStatus $status) => $status->name,
                'choice_value' => fn(?ReservationStatus $status) => $status?->value,
                'label' => 'Statut',
                'disabled' => true,
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
