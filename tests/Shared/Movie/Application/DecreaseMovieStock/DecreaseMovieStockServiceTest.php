<?php

declare(strict_types=1);

namespace App\Tests\Shared\Movie\Application\DecreaseMovieStock;

use App\Shared\Domain\Exception\EntityNotFoundException;
use App\Shared\Movie\Application\DecreaseMovieStock\DecreaseMovieStockRequest;
use App\Shared\Movie\Application\DecreaseMovieStock\DecreaseMovieStockService;
use App\Shared\Movie\Domain\Exception\InsufficientStockException;
use App\Shared\Movie\Domain\MovieRepositoryInterface;
use App\Tests\Shared\Movie\Application\MovieTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DecreaseMovieStockServiceTest extends WebTestCase
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

        $request = new DecreaseMovieStockRequest('');
        $service = new DecreaseMovieStockService($repository);
        $service->execute($request);
    }

    /** @test */
    public function should_decrease_movie_stock_given_movie_with_some_stock()
    {
        $currentStock = 1;
        $movie = $this->getMovie($currentStock);
        $repository = $this->createMock(MovieRepositoryInterface::class);
        $repository->method('getById')->willReturn($movie);

        $request = new DecreaseMovieStockRequest($movie->getId()->getValue());
        $service = new DecreaseMovieStockService($repository);
        $service->execute($request);
        $this->assertEquals($currentStock - 1, $movie->getStock());
    }

    /** @test */
    public function should_throw_error_given_movie_without_stock()
    {
        $currentStock = 0;
        $movie = $this->getMovie($currentStock);
        $repository = $this->createMock(MovieRepositoryInterface::class);
        $repository->method('getById')->willReturn($movie);

        $request = new DecreaseMovieStockRequest($movie->getId()->getValue());
        $service = new DecreaseMovieStockService($repository);
        $this->expectException(InsufficientStockException::class);
        $service->execute($request);
    }

    /** @test */
    public function should_throw_error_given_not_found_movie()
    {
        $repository = $this->createMock(MovieRepositoryInterface::class);
        $repository->method('getById')->willReturn(null);

        $request = new DecreaseMovieStockRequest('');
        $service = new DecreaseMovieStockService($repository);
        $this->expectException(EntityNotFoundException::class);
        $service->execute($request);
    }
}
