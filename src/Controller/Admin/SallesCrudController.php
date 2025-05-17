<?php

namespace App\Controller\Admin;

use App\Entity\Salles;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class SallesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Salles::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('numeroSalle', 'Numéro de la salle'),
            IntegerField::new('nombreSiege', 'Nombre total de sièges')
                ->setHelp('Ce champ doit être égal à Sièges PMR + Places disponibles.'),
            IntegerField::new('nombreSiegePMR', 'Nombre de sièges PMR')
                ->setHelp('Nombre de sièges réservés aux personnes à mobilité réduite.'),
            IntegerField::new('nombrePlacesDisponibles', 'Places disponibles')
                ->setHelp('Nombre de places disponibles après allocation des sièges PMR.')
                ->setFormTypeOption('disabled', true) // Empêche la modification
                ->onlyOnIndex(), // Ne s'affiche que dans les listes ou les détails
        ];
    }
}