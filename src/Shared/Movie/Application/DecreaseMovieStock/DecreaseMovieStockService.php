<?php

declare(strict_types=1);

namespace App\Shared\Movie\Application\DecreaseMovieStock;

use App\Shared\Domain\Exception\EntityNotFoundException;
use App\Shared\Domain\Uuid;
use App\Shared\Movie\Domain\Exception\InsufficientStockException;
use App\Shared\Movie\Domain\MovieRepositoryInterface;

class DecreaseMovieStockService
{
    private MovieRepositoryInterface $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    /**
     * @throws EntityNotFoundException|InsufficientStockException
     */
    public function execute(DecreaseMovieStockRequest $request): void
    {
        $movieId = Uuid::create($request->getMovieId());
        $movie = $this->movieRepository->getById($movieId);
        if (!$movie) {
            throw new EntityNotFoundException('Movie not found');
        }
        $movie->decreaseStock();
        $this->movieRepository->save($movie);
    }
}
