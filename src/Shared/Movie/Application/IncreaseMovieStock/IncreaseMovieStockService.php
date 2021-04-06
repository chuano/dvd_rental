<?php

declare(strict_types=1);

namespace App\Shared\Movie\Application\IncreaseMovieStock;

use App\Shared\Domain\Exception\EntityNotFoundException;
use App\Shared\Domain\Uuid;
use App\Shared\Movie\Domain\MovieRepositoryInterface;

class IncreaseMovieStockService
{
    private MovieRepositoryInterface $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function execute(IncreaseMovieStockRequest $request): void
    {
        $movieId = Uuid::create($request->getMovieId());
        $movie = $this->movieRepository->getById($movieId);
        if (!$movie) {
            throw new EntityNotFoundException('Movie not found');
        }
        $movie->increaseStock();
        $this->movieRepository->save($movie);
    }
}
