<?php

declare(strict_types=1);

namespace App\Tests\Shared\Movie\Application\IncreaseMovieStock;

use App\Shared\Domain\Exception\EntityNotFoundException;
use App\Shared\Movie\Application\IncreaseMovieStock\IncreaseMovieStockRequest;
use App\Shared\Movie\Application\IncreaseMovieStock\IncreaseMovieStockService;
use App\Shared\Movie\Domain\MovieRepositoryInterface;
use App\Tests\Shared\Movie\Application\MovieTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IncreaseMovieStockServiceTest extends WebTestCase
{
    use MovieTestTrait;

    /** @test */
    public function should_call_repository_save_method()
    {
        $currentStock = 1;
        $movie = $this->getMovie($currentStock);
        $repository = $this->createMock(MovieRepositoryInterface::class);
        $repository->method('getById')->willReturn($movie);
        $repository->expects($this->exactly(1))->method('save');

        $request = new IncreaseMovieStockRequest('');
        $service = new IncreaseMovieStockService($repository);
        $service->execute($request);
    }

    /** @test */
    public function should_increase_movie_stock_given_correct_movie()
    {
        $currentStock = 1;
        $movie = $this->getMovie($currentStock);
        $repository = $this->createMock(MovieRepositoryInterface::class);
        $repository->method('getById')->willReturn($movie);

        $request = new IncreaseMovieStockRequest($movie->getId()->getValue());
        $service = new IncreaseMovieStockService($repository);
        $service->execute($request);
        $this->assertEquals($currentStock + 1, $movie->getStock());
    }


    /** @test */
    public function should_throw_error_given_not_found_movie()
    {
        $repository = $this->createMock(MovieRepositoryInterface::class);
        $repository->method('getById')->willReturn(null);

        $request = new IncreaseMovieStockRequest('');
        $service = new IncreaseMovieStockService($repository);
        $this->expectException(EntityNotFoundException::class);
        $service->execute($request);
    }
}
