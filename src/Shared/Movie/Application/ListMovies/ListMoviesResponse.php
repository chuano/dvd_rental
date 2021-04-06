<?php

declare(strict_types=1);

namespace App\Shared\Movie\Application\ListMovies;

use App\Shared\Movie\Domain\Movie;

class ListMoviesResponse
{
    private int $page;
    private int $limit;
    private int $total;
    private array $movies;

    public function __construct(int $page, int $limit, int $total, array $movies)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->total = $total;
        $this->movies = $movies;
    }

    public function getData(): array
    {
        return [
            'page' => $this->page,
            'limit' => $this->limit,
            'total' => $this->total,
            'data' => array_map(fn (Movie $movie) =>  [
                'id' => $movie->getId()->getValue(),
                'title' => $movie->getMetadata()->getTitle(),
                'synopsis' => $movie->getMetadata()->getSynopsis(),
                'year' => $movie->getMetadata()->getYear(),
                'stock' => $movie->getStock(),
            ], $this->movies),
        ];
    }
}
