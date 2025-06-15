<?php

namespace App\Service;

use App\Repository\FilmRepository;
use App\Entity\Film;

final readonly class FilmService
{
    public function __construct(private FilmRepository $filmRepository)
    {
    }

    public function getAllFilms(): array
    {
        return $this->filmRepository->findAllFilms();
    }

    public function getFilmById(int $id): ?Film
    {
        return $this->filmRepository->find($id);
    }

}
