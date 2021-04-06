<?php

declare(strict_types=1);

namespace App\Shared\Movie\Application\ListMovies;

use App\Shared\Movie\Domain\MovieRepositoryInterface;

class ListMoviesService
{
    private MovieRepositoryInterface $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function execute(ListMoviesRequest $request): ListMoviesResponse
    {
        $total = $this->movieRepository->count();
        $movies = $this->movieRepository->getAll($request->getPage(), $request->getLimit());

        return new ListMoviesResponse($request->getPage(), $request->getLimit(), $total, $movies);
    }
}
