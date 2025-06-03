<?php

namespace App\Controller\Admin;

use App\Entity\Reservations;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
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
        yield AssociationField::new('cinemas', 'Cinéma');
        yield AssociationField::new('films', 'Film');
        yield AssociationField::new('seances', 'Séance');
        yield IntegerField::new('nombrePlaces', 'Nombre de places');
        yield TextField::new('status', 'Statut');
        yield DateField::new('date', 'Date');
        yield AssociationField::new('user', 'Utilisateur');
        yield MoneyField::new('prixTotal', 'Prix total')
            ->setCurrency('EUR')
            ->setStoredAsCents(false)
            ->setFormTypeOption('disabled', true);

        if (Crud::PAGE_INDEX === $pageName) {
            yield TextField::new('someComputedField', 'Quick Info');
        }

    }
}