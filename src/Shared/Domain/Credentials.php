<?php

declare(strict_types=1);

namespace App\Shared\Domain;

class Credentials
{
    public const USER_PROFILE = 1;
    public const ADMIN_PROFILE = 2;

    private Uuid $id;
    private int $profile;

    public function __construct(Uuid $id, int $profile)
    {
        $this->id = $id;
        $this->profile = $profile;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getProfile(): int
    {
        return $this->profile;
    }
}
