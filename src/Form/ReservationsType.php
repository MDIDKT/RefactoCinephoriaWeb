<?php

namespace App\Form;

use App\Entity\Cinemas;
use App\Entity\Films;
use App\Entity\Reservations;
use App\Entity\Seance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ReservationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cinemas', EntityType::class, $this->getCinemaFieldOptions())
            ->add('films', EntityType::class, $this->getFilmFieldOptions())
            ->add('seances', EntityType::class, $this->getSeanceFieldOptions())
            ->add('nombrePlaces', IntegerType::class, $this->getNombrePlacesFieldOptions());
    }

    private function getCinemaFieldOptions(): array
    {
        return [
            'class' => Cinemas::class,
            'choice_label' => 'nom',
            'label' => 'Choisir un cinéma',
            'attr' => ['class' => 'form-input mt-1 block w-full'],
        ];
    }

    private function getFilmFieldOptions(): array
    {
        return [
            'class' => Films::class,
            'choice_label' => 'titre',
            'label' => 'Choisir un film',
            'attr' => ['class' => 'form-input mt-1 block w-full'],
        ];
    }

    private function getSeanceFieldOptions(): array
    {
        return [
            'class' => Seance::class,
            'choice_label' => fn(Seance $seance) => $this->formatSeanceChoice($seance),
            'label' => 'Choisir une séance',
            'attr' => ['class' => 'form-input mt-1 block w-full'],
            'placeholder' => 'Choisissez une séance valide',
            'required' => true,
        ];
    }

    private function getNombrePlacesFieldOptions(): array
    {
        return [
            'label' => 'Nombre de places',
            'attr' => ['class' => 'form-input mt-1 block w-full', 'min' => 1],
            'required' => true,
        ];
    }

    private function formatSeanceChoice(Seance $seance): string
    {
        $salleAssociee = $seance->getSalle();
        $placesDisponibles = $salleAssociee ? $seance->getPlacesDisponibles() : 'Indisponibles';

        return sprintf(
            '%s (Places disponibles: %s)',
            $seance->getHeureDebut()->format('d/m/Y H:i'),
            $placesDisponibles
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservations::class,
        ]);
    }
}
