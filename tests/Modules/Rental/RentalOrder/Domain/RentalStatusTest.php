<?php

declare(strict_types=1);

namespace App\Tests\Modules\Rental\RentalOrder\Domain;

use App\Modules\Rental\RentalOrder\Domain\Exception\InvalidRentalOrderStatusException;
use App\Modules\Rental\RentalOrder\Domain\RentalStatus;
use PHPUnit\Framework\TestCase;

class RentalStatusTest extends TestCase
{
    /** @test */
    public function should_throw_error_if_status_not_active_and_not_done()
    {
        $this->expectException(InvalidRentalOrderStatusException::class);
        new RentalStatus('NONE');
    }
}
