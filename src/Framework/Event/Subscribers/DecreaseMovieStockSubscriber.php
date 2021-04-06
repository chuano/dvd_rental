<?php

declare(strict_types=1);

namespace App\Framework\Event\Subscribers;

use App\Modules\Rental\RentalOrder\Domain\Event\RentalOrderCreated;
use App\Shared\Movie\Application\DecreaseMovieStock\DecreaseMovieStockRequest;
use App\Shared\Movie\Application\DecreaseMovieStock\DecreaseMovieStockService;
use App\Shared\Movie\Infra\MovieRepositoryImpl;
use Doctrine\ORM\EntityManagerInterface;

class DecreaseMovieStockSubscriber
{
    private MovieRepositoryImpl $movieRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->movieRepository = new MovieRepositoryImpl($entityManager);
    }

    public function execute(RentalOrderCreated $rentalOrderCreated): void
    {
        $movieId = $rentalOrderCreated->getPayload()->getMovieId()->getValue();
        $decreaseMovieStockRequest = new DecreaseMovieStockRequest($movieId);
        $decreaseMovieStockService = new DecreaseMovieStockService($this->movieRepository);
        $decreaseMovieStockService->execute($decreaseMovieStockRequest);
    }
}
