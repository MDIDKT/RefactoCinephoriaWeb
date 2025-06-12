<?php

namespace App\Form;

use App\Entity\Incident;
use App\Entity\Salle;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Enum\IncidentStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncidentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', null, [
                'widget' => 'single_text'
            ])
            ->add('description')
            ->add('status', ChoiceType::class, [
                'choices' => IncidentStatus::cases(),
                'choice_label' => fn(IncidentStatus $status) => $status->label(),
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('employee', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('salle', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => 'id',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Incident::class,
        ]);
    }
}
