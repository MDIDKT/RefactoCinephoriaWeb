<?php

namespace App\Controller\Admin;

use App\Entity\Films;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class FilmsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Films::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // Champ ID
            IdField::new('id', 'ID')->hideOnForm(), // Masqué dans le formulaire

            // Titre
            TextField::new('titre', 'Titre du Film')->setRequired(true),

            // Description
            TextareaField::new('description', 'Description')->setRequired(true),

            // Âge minimum
            IntegerField::new('ageMinimum', 'Âge Minimum')->setRequired(true),

            // Coup de cœur
            BooleanField::new('coupDeCoeur', 'Coup de Coeur'),

            // Note sur 10
            NumberField::new('note', 'Note (sur 10)')
                ->setNumDecimals(1)
                ->setRequired(true),

            // Qualité vidéo
            TextField::new('qualite', 'Qualité Vidéo')->setRequired(true),

            // Champ pour l'image
            ImageField::new('imageName', 'Affiche')
                ->setBasePath('/images/films') // Chemin relatif pour afficher les images
                ->setUploadDir('public/images/films') // Chemin absolu où stocker les images
                ->setRequired(false), // Champ facultatif

            // Association avec les séances
            AssociationField::new('seances', 'Séances Associées')->hideOnForm(),

            // Association avec les avis
            AssociationField::new('avis', 'Avis')->hideOnForm(),

            // Association avec les cinémas
            AssociationField::new('cinemas', 'Cinémas Associés')->setRequired(false),
        ];
    }
}