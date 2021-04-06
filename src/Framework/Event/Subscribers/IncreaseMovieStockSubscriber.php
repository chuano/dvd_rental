<?php

declare(strict_types=1);

namespace App\Framework\Event\Subscribers;

use App\Modules\Rental\RentalOrder\Domain\Event\RentalOrderFinished;
use App\Shared\Movie\Application\IncreaseMovieStock\IncreaseMovieStockRequest;
use App\Shared\Movie\Application\IncreaseMovieStock\IncreaseMovieStockService;
use App\Shared\Movie\Infra\MovieRepositoryImpl;
use Doctrine\ORM\EntityManagerInterface;

class IncreaseMovieStockSubscriber
{
    private MovieRepositoryImpl $movieRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->movieRepository = new MovieRepositoryImpl($entityManager);
    }

    public function execute(RentalOrderFinished $rentalOrderCreated): void
    {
        $movieId = $rentalOrderCreated->getPayload()->getMovieId()->getValue();
        $increaseMovieStockRequest = new IncreaseMovieStockRequest($movieId);
        $increaseMovieStockService = new IncreaseMovieStockService($this->movieRepository);
        $increaseMovieStockService->execute($increaseMovieStockRequest);
    }
}
