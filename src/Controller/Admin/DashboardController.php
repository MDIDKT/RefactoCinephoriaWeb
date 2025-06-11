<?php

namespace App\Controller\Admin;

use App\Entity\Cinema;
use App\Entity\Film;
use App\Entity\Salle;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Container\ContainerExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin_index')]
    // #[isGranted('ROLE_ADMIN', message: 'Vous devez être administrateur pour accéder à cette page.')]
    public function index(): Response
    {
        try {
            $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
            return $this->redirect(
                $adminUrlGenerator->setController(FilmsCrudController::class)->generateUrl()
            );
        } catch (ContainerExceptionInterface) {
            // Gérer l'exception si le service AdminUrlGenerator n'est pas disponible
            return $this->render('home/index.html.twig', [
                'message' => 'Le tableau de bord d\'administration est indisponible pour le moment.',
            ]);
        }
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration du Cinéma');
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    public function configureCrud(): Crud
    {
        return Crud::new()
            ->showEntityActionsInlined();
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Cinéma', 'fas fa-building', Cinema::class);
        yield MenuItem::linkToCrud('Films', 'fas fa-film', Film::class);
        yield MenuItem::linkToCrud('Salles', 'fas fa-chair', Salle::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
    }
}
