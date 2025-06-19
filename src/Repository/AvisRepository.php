<?php

namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct();
    }

    //recuperer tous les avis
    public function findAllAvis(): array
    {
        return $this->findAll();
    }

    //recuperation des avis par film et utilisateur
    public function findAvisByFilmAndUser(int $filmId, int $userId): ?Avis
    {
        return $this->findOneBy(['film' => $filmId, 'user' => $userId]);
    }

    // Enregistrement d'un avis
    public function save(Avis $avis): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($avis);
        $entityManager->flush();
    }
}
