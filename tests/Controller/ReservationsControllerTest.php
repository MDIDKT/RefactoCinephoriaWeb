<?php

namespace App\Tests\Controller;

use App\Entity\Reservations;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ReservationsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/reservations/';

    protected function setUp (): void
    {
        $this->client = static::createClient ();
        $this->manager = static::getContainer ()->get ('doctrine')->getManager ();
        $this->repository = $this->manager->getRepository (Reservations::class);

        foreach ($this->repository->findAll () as $object) {
            $this->manager->remove ($object);
        }

        $this->manager->flush ();
    }

    public function testIndex (): void
    {
        $this->client->followRedirects ();
        $crawler = $this->client->request ('GET', $this->path);

        self::assertResponseStatusCodeSame (200);
        self::assertPageTitleContains ('Reservation index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew (): void
    {
        $this->markTestIncomplete ();
        $this->client->request ('GET', sprintf ('%snew', $this->path));

        self::assertResponseStatusCodeSame (200);

        $this->client->submitForm ('Save', [
            'reservation[nombrePlaces]' => 'Testing',
            'reservation[typePMR]' => 'Testing',
            'reservation[prixTotal]' => 'Testing',
            'reservation[cinemas]' => 'Testing',
            'reservation[films]' => 'Testing',
            'reservation[seances]' => 'Testing',
        ]);

        self::assertResponseRedirects ($this->path);

        self::assertSame (1, $this->repository->count ([]));
    }

    public function testShow (): void
    {
        $this->markTestIncomplete ();
        $fixture = new Reservations();
        $fixture->setNombrePlaces ('My Title');
        $fixture->setTypePMR ('My Title');
        $fixture->setPrixTotal ('My Title');
        $fixture->setCinemas ('My Title');
        $fixture->setFilms ('My Title');
        $fixture->setSeances ('My Title');

        $this->manager->persist ($fixture);
        $this->manager->flush ();

        $this->client->request ('GET', sprintf ('%s%s', $this->path, $fixture->getId ()));

        self::assertResponseStatusCodeSame (200);
        self::assertPageTitleContains ('Reservation');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit (): void
    {
        $this->markTestIncomplete ();
        $fixture = new Reservations();
        $fixture->setNombrePlaces ('Value');
        $fixture->setTypePMR ('Value');
        $fixture->setPrixTotal ('Value');
        $fixture->setCinemas ('Value');
        $fixture->setFilms ('Value');
        $fixture->setSeances ('Value');

        $this->manager->persist ($fixture);
        $this->manager->flush ();

        $this->client->request ('GET', sprintf ('%s%s/edit', $this->path, $fixture->getId ()));

        $this->client->submitForm ('Update', [
            'reservation[nombrePlaces]' => 'Something New',
            'reservation[typePMR]' => 'Something New',
            'reservation[prixTotal]' => 'Something New',
            'reservation[cinemas]' => 'Something New',
            'reservation[films]' => 'Something New',
            'reservation[seances]' => 'Something New',
        ]);

        self::assertResponseRedirects ('/reservations/');

        $fixture = $this->repository->findAll ();

        self::assertSame ('Something New', $fixture[0]->getNombrePlaces ());
        self::assertSame ('Something New', $fixture[0]->getTypePMR ());
        self::assertSame ('Something New', $fixture[0]->getPrixTotal ());
        self::assertSame ('Something New', $fixture[0]->getCinemas ());
        self::assertSame ('Something New', $fixture[0]->getFilms ());
        self::assertSame ('Something New', $fixture[0]->getSeances ());
    }

    public function testRemove (): void
    {
        $this->markTestIncomplete ();
        $fixture = new Reservations();
        $fixture->setNombrePlaces ('Value');
        $fixture->setTypePMR ('Value');
        $fixture->setPrixTotal ('Value');
        $fixture->setCinemas ('Value');
        $fixture->setFilms ('Value');
        $fixture->setSeances ('Value');

        $this->manager->persist ($fixture);
        $this->manager->flush ();

        $this->client->request ('GET', sprintf ('%s%s', $this->path, $fixture->getId ()));
        $this->client->submitForm ('Delete');

        self::assertResponseRedirects ('/reservations/');
        self::assertSame (0, $this->repository->count ([]));
    }
}
