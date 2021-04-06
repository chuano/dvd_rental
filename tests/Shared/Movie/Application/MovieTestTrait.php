<?php


namespace App\Tests\Shared\Movie\Application;


use App\Shared\Movie\Domain\Movie;
use App\Shared\Movie\Domain\MovieMetadata;
use Ramsey\Uuid\Uuid;

trait MovieTestTrait
{
    private function getMovie(int $stock): Movie
    {
        $movieId = Uuid::uuid4()->toString();
        $metadata = new MovieMetadata('title', 2001, 'synopsis');

        return new Movie(\App\Shared\Domain\Uuid::create($movieId), $metadata, $stock);
    }
}
