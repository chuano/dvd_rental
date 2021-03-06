<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

class ForbiddenException extends CustomException
{
    private const ERROR_MESSAGE = 'Forbidden';
    private const ERROR_CODE = 403;

    public function __construct()
    {
        parent::__construct(self::ERROR_MESSAGE, self::ERROR_CODE);
    }
}
