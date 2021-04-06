<?php

declare(strict_types=1);

namespace App\Shared\Movie\Domain;

class MovieMetadata
{
    private string $title;
    private int $year;
    private string $synopsis;

    public function __construct(string $title, int $year, string $synopsis)
    {
        $this->title = $title;
        $this->year = $year;
        $this->synopsis = $synopsis;
    }

    public static function create(string $title, int $year, string $synopsis): self
    {
        return new self($title, $year, $synopsis);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getSynopsis(): string
    {
        return $this->synopsis;
    }
}
