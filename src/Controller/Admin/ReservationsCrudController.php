<?php

namespace App\Controller\Admin;

use App\Entity\Reservations;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReservationsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservations::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // Champ pour associer un cinéma
            AssociationField::new('cinemas', 'Cinéma'),

            // Champ pour associer un film
            AssociationField::new('films', 'Film'),

            // Champ pour associer une séance
            AssociationField::new('seances', 'Séance'),

            // Champ pour le nombre de places
            IntegerField::new('nombrePlaces', 'Nombre de places'),

            // Champ pour le statut (par exemple : "Réservé", "Annulé")
            TextField::new('status', 'Statut'),

            // Champ pour la date
            DateField::new('date', 'Date'),

            // Champ pour associer un utilisateur
            AssociationField::new('user', 'Utilisateur'),

            // Champ pour le prix total
            MoneyField::new('prixTotal', 'Prix total')
                ->setCurrency('EUR') // Définir la devise
                ->setStoredAsCents(false) // Si vous stockez le prix en euros directement
                ->setFormTypeOption('disabled', true), // Empêche l'édition de ce champ dans le formulaire
        ];
    }
}
