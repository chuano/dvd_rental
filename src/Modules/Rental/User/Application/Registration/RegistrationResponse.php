<?php

declare(strict_types=1);

namespace App\Modules\Rental\User\Application\Registration;

use App\Modules\Rental\User\Domain\User;

class RegistrationResponse
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getData(): array
    {
        return [
            'id' => $this->user->getId()->getValue(),
            'email' => $this->user->getEmail()->getValue(),
        ];
    }
}
