<?php

namespace App\Tests\Controller;

use App\Entity\Avis;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AvisControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/avis/';

    protected function setUp (): void
    {
        $this->client = static::createClient ();
        $this->manager = static::getContainer ()->get ('doctrine')->getManager ();
        $this->repository = $this->manager->getRepository (Avis::class);

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
        self::assertPageTitleContains ('Avi index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew (): void
    {
        $this->markTestIncomplete ();
        $this->client->request ('GET', sprintf ('%snew', $this->path));

        self::assertResponseStatusCodeSame (200);

        $this->client->submitForm ('Save', [
            'avi[commentaire]' => 'Testing',
            'avi[note]' => 'Testing',
            'avi[approuve]' => 'Testing',
            'avi[film]' => 'Testing',
        ]);

        self::assertResponseRedirects ($this->path);

        self::assertSame (1, $this->repository->count ([]));
    }

    public function testShow (): void
    {
        $this->markTestIncomplete ();
        $fixture = new Avis();
        $fixture->setCommentaire ('My Title');
        $fixture->setNote ('My Title');
        $fixture->setApprouve ('My Title');
        $fixture->setFilm ('My Title');

        $this->manager->persist ($fixture);
        $this->manager->flush ();

        $this->client->request ('GET', sprintf ('%s%s', $this->path, $fixture->getId ()));

        self::assertResponseStatusCodeSame (200);
        self::assertPageTitleContains ('Avi');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit (): void
    {
        $this->markTestIncomplete ();
        $fixture = new Avis();
        $fixture->setCommentaire ('Value');
        $fixture->setNote ('Value');
        $fixture->setApprouve ('Value');
        $fixture->setFilm ('Value');

        $this->manager->persist ($fixture);
        $this->manager->flush ();

        $this->client->request ('GET', sprintf ('%s%s/edit', $this->path, $fixture->getId ()));

        $this->client->submitForm ('Update', [
            'avi[commentaire]' => 'Something New',
            'avi[note]' => 'Something New',
            'avi[approuve]' => 'Something New',
            'avi[film]' => 'Something New',
        ]);

        self::assertResponseRedirects ('/avis/');

        $fixture = $this->repository->findAll ();

        self::assertSame ('Something New', $fixture[0]->getCommentaire ());
        self::assertSame ('Something New', $fixture[0]->getNote ());
        self::assertSame ('Something New', $fixture[0]->getApprouve ());
        self::assertSame ('Something New', $fixture[0]->getFilm ());
    }

    public function testRemove (): void
    {
        $this->markTestIncomplete ();
        $fixture = new Avis();
        $fixture->setCommentaire ('Value');
        $fixture->setNote ('Value');
        $fixture->setApprouve ('Value');
        $fixture->setFilm ('Value');

        $this->manager->persist ($fixture);
        $this->manager->flush ();

        $this->client->request ('GET', sprintf ('%s%s', $this->path, $fixture->getId ()));
        $this->client->submitForm ('Delete');

        self::assertResponseRedirects ('/avis/');
        self::assertSame (0, $this->repository->count ([]));
    }
}
