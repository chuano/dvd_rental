<?php

declare(strict_types=1);

namespace App\Shared\Movie\Domain\Exception;

use App\Shared\Domain\Exception\CustomException;

class InsufficientStockException extends CustomException
{
    private const ERROR_MESSAGE = 'Inssuficient stock';
    private const ERROR_CODE = 400;

    public function __construct()
    {
        parent::__construct(self::ERROR_MESSAGE, self::ERROR_CODE);
    }
}
