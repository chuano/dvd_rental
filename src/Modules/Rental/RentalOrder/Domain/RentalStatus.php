<?php

declare(strict_types=1);

namespace App\Modules\Rental\RentalOrder\Domain;

use App\Modules\Rental\RentalOrder\Domain\Exception\InvalidRentalOrderStatusException;

class RentalStatus
{
    public const ACTIVE = 'ACTIVE';
    public const DONE = 'DONE';

    private string $status;

    /**
     * @throws InvalidRentalOrderStatusException
     */
    public function __construct(string $status)
    {
        $this->ensureValidStatus($status);
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @throws InvalidRentalOrderStatusException
     */
    private function ensureValidStatus(string $status): void
    {
        if ($status !== self::ACTIVE && $status !== self::DONE) {
            throw new InvalidRentalOrderStatusException();
        }
    }
}
