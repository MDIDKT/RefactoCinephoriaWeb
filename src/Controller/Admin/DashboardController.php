<?php

namespace App\Controller\Admin;

use App\Entity\Cinemas;
use App\Entity\Films;
use App\Entity\Reservations;
use App\Entity\Salles;
use App\Entity\Seance;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect(
            $adminUrlGenerator->setController(FilmsCrudController::class)->generateUrl()
        );
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration du Cinéma');
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->showEntityActionsInlined();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Cinémas', 'fas fa-building', Cinemas::class);
        yield MenuItem::linkToCrud('Films', 'fas fa-film', Films::class);
        yield MenuItem::linkToCrud('Séances', 'fas fa-calendar-alt', Seance::class);
        yield MenuItem::linkToCrud('Salles', 'fas fa-chair', Salles::class);
        yield MenuItem::linkToCrud('Réservations', 'fas fa-ticket-alt', Reservations::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
    }
}
