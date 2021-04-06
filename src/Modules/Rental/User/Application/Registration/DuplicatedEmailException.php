<?php

declare(strict_types=1);

namespace App\Modules\Rental\User\Application\Registration;

use App\Shared\Domain\Exception\CustomException;

class DuplicatedEmailException extends CustomException
{
    private const ERROR_MESSAGE = 'Duplcated email address';
    private const ERROR_CODE = 409;

    public function __construct()
    {
        parent::__construct(self::ERROR_MESSAGE, self::ERROR_CODE);
    }
}
