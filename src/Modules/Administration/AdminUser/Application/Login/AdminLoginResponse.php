<?php

declare(strict_types=1);

namespace App\Modules\Administration\AdminUser\Application\Login;

class AdminLoginResponse
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
