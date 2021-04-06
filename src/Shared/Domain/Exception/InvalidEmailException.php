<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use Exception;

class InvalidEmailException extends CustomException
{
    private const ERROR_MESSAGE = 'Invalid email address';
    private const ERROR_CODE = 400;

    public function __construct()
    {
        parent::__construct(self::ERROR_MESSAGE, self::ERROR_CODE);
    }
}
