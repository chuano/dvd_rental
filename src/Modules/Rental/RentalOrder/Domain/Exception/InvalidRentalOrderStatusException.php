<?php

declare(strict_types=1);

namespace App\Modules\Rental\RentalOrder\Domain\Exception;

use App\Shared\Domain\Exception\CustomException;

class InvalidRentalOrderStatusException extends CustomException
{
    private const ERROR_MESSAGE = 'Invalid order status';
    private const ERROR_CODE = 400;

    public function __construct()
    {
        parent::__construct(self::ERROR_MESSAGE, self::ERROR_CODE);
    }
}
