<?php

declare(strict_types=1);

namespace App\Shared\Movie\Application\DeleteMovie;

class DeleteMovieRequest
{
    private string $id;
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

    public function getUserProfile(): int
    {
        return $this->userProfile;
    }

    public function setUserProfile(int $userProfile): void
    {
        $this->userProfile = $userProfile;
    }
}
