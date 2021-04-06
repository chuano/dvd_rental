<?php

declare(strict_types=1);

namespace App\Modules\Rental\RentalOrder\Domain\Event;

use App\Modules\Rental\RentalOrder\Domain\RentalOrder;
use App\Shared\Domain\Event\DomainEventInterface;
use DateTimeImmutable;

class RentalOrderFinished implements DomainEventInterface
{
    public const NAME = 'rental_order.finished';

    private DateTimeImmutable $ocurredOn;
    private RentalOrder $rentalOrder;

    public function __construct(RentalOrder $rentalOrder)
    {
        $this->ocurredOn = new DateTimeImmutable();
        $this->rentalOrder = $rentalOrder;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getOcurredOn(): DateTimeImmutable
    {
        return $this->ocurredOn;
    }

    public function getPayload(): RentalOrder
    {
        return $this->rentalOrder;
    }
}
