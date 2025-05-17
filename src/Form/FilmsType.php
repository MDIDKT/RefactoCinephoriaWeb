<?php

namespace App\Form;

use App\Entity\Cinemas;
use App\Entity\Films;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class FilmsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', null, [
                'label' => 'Titre du film',
            ])
            ->add('description', null, [
                'label' => 'Description',
            ])
            ->add('imageFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
                'download_label' => 'Télécharger',
                'asset_helper' => true,
                'delete_label' => 'Supprimer',
            ])
            ->add('ageMinimum', null, [
                'label' => 'Âge minimum',
            ])
            ->add('coupDeCoeur', null, [
                'label' => 'Coup de coeur',
            ])
            ->add('note', null, [
                'label' => 'Note',
            ])
            ->add('qualite', null, [
                'label' => 'Qualité vidéo',
            ])
            ->add('cinemas', EntityType::class, [
                'class' => Cinemas::class,
                'choice_label' => 'nom', // Remplacez 'nom' par l'attribut à afficher dans le formulaire
                'multiple' => true, // Permet de sélectionner plusieurs cinémas
                'expanded' => false, // Utilisez 'true' pour afficher sous forme de cases à cocher
                'label' => 'Cinémas associés',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Films::class,
        ]);
    }
}