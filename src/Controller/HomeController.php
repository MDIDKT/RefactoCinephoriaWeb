<?php

namespace App\Controller;

use App\Entity\Films;
use App\Repository\FilmsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index (FilmsRepository $filmsRepository, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {


        $films = $filmsRepository->findAll ();
        return $this->render ('home/index.html.twig', [
            'films' => $films,
            'filmsLast' => $filmsRepository->find3LastFilms (),
        ]);
    }

}
