<?php

declare(strict_types=1);

namespace App\Shared\Movie\Application\UpdateMovie;

use App\Shared\Movie\Domain\Movie;

class UpdateMovieResponse
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
