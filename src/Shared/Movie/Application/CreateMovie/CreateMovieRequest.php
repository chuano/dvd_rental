<?php

declare(strict_types=1);

namespace App\Shared\Movie\Application\CreateMovie;

class CreateMovieRequest
{
    private string $uuid;
    private string $title;
    private string $synopsis;
    private int $year;
    private int $stock;
    private int $userProfile;

    public function __construct()
    {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSynopsis(): string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): void
    {
        $this->synopsis = $synopsis;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function getUserProfile(): int
    {
        return $this->userProfile;
    }

    public function setUserProfile(int $userProfile): void
    {
        $this->userProfile = $userProfile;
    }
}
