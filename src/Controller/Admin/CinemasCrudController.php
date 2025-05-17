<?php

namespace App\Controller\Admin;

use App\Entity\Cinemas;
use Doctrine\Common\Collections\Collection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use SebastianBergmann\CodeCoverage\Report\Text;

class CinemasCrudController extends AbstractCrudController
{
    public static function getEntityFqcn (): string
    {
        return Cinemas::class;
    }

    public function configureFields (string $pageName): iterable
    {
        return [
            TextField::new ('nom', 'Cinéma'),
            TextField::new ('ville', 'Villes'),
            TextField::new ('adresse', 'Adresses'),
        ];
    }

}
