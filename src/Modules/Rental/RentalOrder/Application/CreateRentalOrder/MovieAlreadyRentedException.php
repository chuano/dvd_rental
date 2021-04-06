<?php

declare(strict_types=1);

namespace App\Modules\Rental\RentalOrder\Application\CreateRentalOrder;

use App\Shared\Domain\Exception\CustomException;

class MovieAlreadyRentedException extends CustomException
{
    private const ERROR_MESSAGE = 'Movie already rented by the user';
    private const ERROR_CODE = 403;

    public function __construct()
    {
        parent::__construct(self::ERROR_MESSAGE, self::ERROR_CODE);
    }
}
