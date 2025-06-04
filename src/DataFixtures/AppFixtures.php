<?php

namespace App\DataFixtures;

use App\Entity\Cinemas;
use App\Entity\Films;
use App\Entity\User;
use App\Entity\Salles;
use App\Entity\Seance;
use App\Entity\Reservations;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr_FR');
        $faker->unique(true);

        for ($i = 0; $i < 10; ++$i) {
            $cinema = new Cinemas();
            $cinema->setNom($faker->company);
            $cinema->setVille($faker->city());
            $cinema->setAdresse($faker->address);
            $manager->persist($cinema);
        }
        for ($i = 0; $i < 10; ++$i) {
            $film = new Films();
            $film->setTitre($faker->sentence(3));
            $film->setDescription($faker->paragraph());
            $film->setAgeMinimum($faker->numberBetween(10, 18));
            $film->setNote($faker->numberBetween(1, 5));
            $film->setImageName($faker->imageUrl(640, 480, 'films'));
            $film->setImageSize($faker->numberBetween(1000, 1000000));
            $film->setCoupDeCoeur($faker->boolean);
            $film->setQualite($faker->randomElement(['3D', '4K']));
            $manager->persist($film);
        }

        for ($i = 0; $i < 10; ++$i) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword($faker->password);
            $user->setPrenom($faker->firstName);
            $user->setNom($faker->lastName);
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }

        for ($i = 0; $i < 10; ++$i) {
            $salle = new Salles();
            $salle->setNombrePlaces($faker->numberBetween(10, 100));
            $salle->setNombreSiege($faker->numberBetween(10, 100));
            $salle->setNombreSiegePMR($faker->numberBetween(10, 100));
            $salle->setNumeroSalle($faker->numberBetween(10, 100));
            $salle->setNombrePlacesDisponibles($faker->numberBetween(10, 100));
            $manager->persist($salle);
        }

        for ($i = 0; $i < 10; ++$i) {
            $seance = new Seance();
            $seance->setCinemas($faker->randomElement($manager->getRepository(Cinemas::class)->findAll()));
            $seance->setFilms($faker->randomElement($manager->getRepository(Films::class)->findAll()));
            $seance->setNombrePlaces($faker->numberBetween(10, 100));
            $seance->setHeureDebut(\DateTimeImmutable::createFromMutable(
                $faker->dateTimeBetween('now', '+1 hour')
            ));

            $seance->setHeureFin(\DateTimeImmutable::createFromMutable(
                $faker->dateTimeBetween('now', '+2 hours')
            ));
            $seance->setQualite($faker->randomElement(['2D', '3D', '4K']));
            $seance->setSalle($faker->randomElement($manager->getRepository(Salles::class)->findAll()));
            $manager->persist($seance);
        }

        for ($i = 0; $i < 10; ++$i) {
            $reservation = new Reservations();
            $reservation->setCinemas($faker->randomElement($manager->getRepository(Cinemas::class)->findAll()));
            $reservation->setFilms($faker->randomElement($manager->getRepository(Films::class)->findAll()));
            $reservation->setSeances($faker->randomElement($manager->getRepository(Seance::class)->findAll()));
            $reservation->setSalle($faker->randomElement($manager->getRepository(Salles::class)->findAll()));
            $reservation->setUser($faker->randomElement($manager->getRepository(User::class)->findAll()));
            $reservation->setNombrePlaces($faker->numberBetween(10, 100));
            $reservation->setPrixTotal($faker->randomFloat(2, 10, 100));
            $reservation->setSalle($faker->randomElement($manager->getRepository(Salles::class)->findAll()));
            $reservation->setUser($faker->randomElement($manager->getRepository(User::class)->findAll()));
            $reservation->setDate(\DateTimeImmutable::createFromMutable(
                $faker->dateTimeBetween('now', '+1 month')
            ));
            $manager->persist($reservation);
        }

        $manager->flush();
    }
}
