<?php

namespace App\Repository;

use App\Entity\Film;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Film>
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    //recuperer tous les films
    public function findAllFilms(): array
    {
        return $this->findBy([], ['id' => 'DESC']);
    }

    // recuperer un film par son id
    public function findFilmById(int $id): ?Film
    {
        return $this->find($id);
    }

    // recuperer les film du dernier mercredi
    public function findFilmsLastWednesday(): array
    {
        $lastWednesday = new DateTime('last wednesday');
        return $this->createQueryBuilder('f')
            ->andWhere('f.dateAjout >= :lastWednesday')
            ->setParameter('lastWednesday', $lastWednesday)
            ->orderBy('f.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // recuperer les films selon les filtres
    public function findFilmsByFilters(?string $cinema, ?string $genre, ?DateTimeInterface $jour): array
    {
        $qb = $this->createQueryBuilder('f');

        if ($cinema) {
            $qb->andWhere('f.cinema = :cinema')
                ->setParameter('cinema', $cinema);
        }

        if ($genre) {
            $qb->andWhere('f.genre = :genre')
                ->setParameter('genre', $genre);
        }

        if ($jour) {
            $qb->andWhere('f.dateSortie = :jour')
                ->setParameter('jour', $jour);
        }

        return $qb->getQuery()->getResult();
    }

    // calculer la note moyenne d'un film
    public function calculateAverageRating(Film $film): float
    {
        $ratings = $film->getAvis()->filter(function ($avis) {
            return $avis->isValide();
        })->map(function ($avis) {
            return $avis->getNote();
        });

        if ($ratings->isEmpty()) {
            return 0.0;
        }

        return array_sum($ratings->toArray()) / count($ratings);
    }

    // vérifier si le film est un coup de cœur
    public function isCoupDeCoeur(Film $film): bool
    {
        return $film->isCoupDeCoeur() === true;
    }
}
