<?php

declare(strict_types=1);

namespace App\Shared\Movie\Application\DecreaseMovieStock;

class DecreaseMovieStockRequest
{
    private string $movieId;

    public function __construct(string $movieId)
    {
        $this->movieId = $movieId;
    }

    public function getMovieId(): string
    {
        return $this->movieId;
    }
}
