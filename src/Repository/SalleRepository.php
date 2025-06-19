<?php

namespace App\Repository;

use App\Entity\Salle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Salle>
 */
class SalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salle::class);
    }

    // liste de toutes les salles
    public function findAllSalles(): array
    {
        return $this->findBy([], ['numero' => 'ASC']);
    }

    //liste des salles d'un cinéma
    public function findSallesByCinema($cinemaId): array
    {
        return $this->findBy(['cinema' => $cinemaId], ['numero' => 'ASC']);
    }

    //nouvelle salle
    public function save(Salle $salle): void
    {
        $this->_em->persist($salle);
        $this->_em->flush();
    }
}
