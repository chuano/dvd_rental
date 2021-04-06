<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

class InvalidCredentialsException extends CustomException
{
    private const ERROR_MESSAGE = 'Invalid credentials';
    private const ERROR_CODE = 401;

    public function __construct()
    {
        parent::__construct(self::ERROR_MESSAGE, self::ERROR_CODE);
    }
}
