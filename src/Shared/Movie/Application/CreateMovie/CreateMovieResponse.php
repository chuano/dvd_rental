<?php

declare(strict_types=1);

namespace App\Shared\Movie\Application\CreateMovie;

use App\Shared\Movie\Domain\Movie;

class CreateMovieResponse
{
    private Movie $movie;

    public function __construct(Movie $movie)
    {
        $this->movie = $movie;
    }

    public function getData(): array
    {
        return [
            'id' => $this->movie->getId()->getValue(),
            'title' => $this->movie->getMetadata()->getTitle(),
            'synopsis' => $this->movie->getMetadata()->getSynopsis(),
            'year' => $this->movie->getMetadata()->getYear(),
            'stock' => $this->movie->getStock(),
        ];
    }
}
