<?php

declare(strict_types=1);

namespace App\Modules\Rental\User\Application\Login;

class LoginResponse
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getData(): array
    {
        return [
            'token' => $this->token
        ];
    }
}
