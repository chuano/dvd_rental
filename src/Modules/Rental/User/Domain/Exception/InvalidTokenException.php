<?php

declare(strict_types=1);

namespace App\Modules\Rental\User\Domain\Exception;

use App\Shared\Domain\Exception\CustomException;

class InvalidTokenException extends CustomException
{
    private const ERROR_MESSAGE = 'Invalid credentials';
    private const ERROR_CODE = 401;

    public function __construct()
    {
        parent::__construct(self::ERROR_MESSAGE, self::ERROR_CODE);
    }
}
