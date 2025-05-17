<?php

namespace App\Controller\Admin;

use App\Entity\Seance;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class SeanceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn (): string
    {
        return Seance::class;
    }


    public function configureFields (string $pageName): iterable
    {
        return [
            DateTimeField::new ('HeureDebut'),
            AssociationField::new ('films'),
            AssociationField::new ('cinemas'),
            AssociationField::new ('salle'),
            IntegerField::new ('nombrePlaces'),
            TimeField::new ('HeureFin'),
        ];
    }

}
