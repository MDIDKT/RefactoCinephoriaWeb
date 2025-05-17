<?php

namespace App\DataFixtures;

use App\Entity\Avis;
use App\Entity\Cinemas;
use App\Entity\Films;
use App\Entity\Reservations;
use App\Entity\Salles;
use App\Entity\Seance;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load (ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create ('fr_FR');


        for ($i = 0; $i < 10; $i++) {
            $cinema = new Cinemas();
            $cinema->setNom ($faker->company);
            $cinema->setVille ($faker->city ());
            $cinema->setAdresse ($faker->address);
            $manager->persist ($cinema);
        }
        for ($i = 0; $i < 10; $i++) {
            $film = new Films();
            $film->setTitre ($faker->sentence (3));
            $film->setDescription($faker->paragraph (3));
            $film->setAgeMinimum($faker->numberBetween (10, 18));
            $film->setNote($faker->numberBetween (1, 5));
            $film->setImageName($faker->imageUrl (640, 480, 'films'));
            $film->setImageSize($faker->numberBetween (1000, 1000000));
            $film->setCoupDeCoeur($faker->boolean);
            $film->setQualite($faker->randomElement(['3D', '4K']));
            $manager->persist ($film);

        }


        $manager->flush ();
    }
}
