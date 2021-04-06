<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

class EntityNotFoundException extends CustomException
{
    private const ERROR_CODE = 404;

    public function __construct(string $message)
    {
        parent::__construct($message, self::ERROR_CODE);
    }
}
