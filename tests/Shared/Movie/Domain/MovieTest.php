<?php

declare(strict_types=1);

namespace App\Tests\Shared\Movie\Domain;

use App\Shared\Domain\Event\DomainEventDispatcher;
use App\Shared\Movie\Domain\Event\MovieUpdated;
use App\Shared\Movie\Domain\Exception\InsufficientStockException;
use App\Shared\Movie\Domain\MovieMetadata;
use App\Tests\Shared\Movie\Application\MovieTestTrait;
use PHPUnit\Framework\TestCase;

class MovieTest extends TestCase
{
    use MovieTestTrait;

    /** @test */
    public function should_publis_event_on_update()
    {
        $movie = $this->getMovie(0);
        $movie->update(new MovieMetadata('title', 2000, 'synopsis'), 1);
        $this->assertInstanceOf(MovieUpdated::class, DomainEventDispatcher::getInstance()->getEvents()[0]);
    }

    /** @test */
    public function should_decrease_stock()
    {
        $movie = $this->getMovie(1);
        $movie->decreaseStock();
        $this->assertEquals(0, $movie->getStock());
    }

    /** @test */
    public function should_increase_stock()
    {
        $movie = $this->getMovie(1);
        $movie->increaseStock();
        $this->assertEquals(2, $movie->getStock());
    }

    /** @test */
    public function should_throw_error_decreasing_stock_given_movie_without_stock()
    {
        $movie = $this->getMovie(0);
        $this->expectException(InsufficientStockException::class);
        $movie->decreaseStock();
    }
}
