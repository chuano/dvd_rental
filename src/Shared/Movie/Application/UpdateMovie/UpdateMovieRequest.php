<?php

declare(strict_types=1);

namespace App\Shared\Movie\Application\UpdateMovie;

class UpdateMovieRequest
{
    private string $id;
    private string $title;
    private string $synopsis;
    private int $year;
    private int $stock;
    private int $userProfile;

    public function __construct()
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
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
