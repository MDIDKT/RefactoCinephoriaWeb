<?php

namespace App\DataFixtures;

use App\Entity\Cinema;
use App\Entity\Film;
use App\Entity\User;
use App\Entity\Salle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->unique(true);

        for ($i = 0; $i < 5; ++$i) {
            $cinema = new Cinema();
            $cinema->setNom($faker->company);
            $cinema->setAdresse($faker->address);
            $manager->persist($cinema);
        }
        for ($i = 0; $i < 10; ++$i) {
            $film = new Film();
            $film->setTitre($faker->sentence(3));
            $film->setDescription($faker->paragraph());
            $film->setAgeMinimum($faker->numberBetween(10, 18));
            $film->setImageName($faker->imageUrl(640, 480, 'films'));
            $film->setImageSize($faker->numberBetween(1000, 1000000));
            $film->setCoupDeCoeur($faker->boolean);
            $manager->persist($film);
        }

        for ($i = 0; $i < 5; ++$i) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword($faker->password);
            $user->setPrenom($faker->firstName);
            $user->setNom($faker->lastName);
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }

        /*for ($i = 0; $i < 5; ++$i) {
            $salle = new Salle();
            $salle->setNombrePlaces($faker->numberBetween(1, 100));
            $salle->setNombreSiege($faker->numberBetween(10, 100));
            $salle->setNombreSiegePMR($faker->numberBetween(10, 50));
            $salle->setNumeroSalle($faker->numberBetween(10, 10));
            $manager->persist($salle);
        }*/

        $manager->flush();
    }
}
