<?php

declare(strict_types=1);

namespace App\Modules\Rental\RentalOrder\Application\CreateRentalOrder;

use App\Modules\Rental\RentalOrder\Domain\RentalOrder;

class CreateRentalOrderResponse
{
    private RentalOrder $rentalOrder;

    public function __construct(RentalOrder $rentalOrder)
    {
        $this->rentalOrder = $rentalOrder;
    }

    public function getData(): array
    {
        return [
            'id' => $this->rentalOrder->getId()->getValue(),
            'status' => $this->rentalOrder->getStatus()->getStatus(),
            'userId' => $this->rentalOrder->getUserId()->getValue(),
            'from' => $this->rentalOrder->getInterval()->getFrom()->format(DATE_ISO8601),
            'to' => $this->rentalOrder->getInterval()->getTo()->format(DATE_ISO8601),
            'movieId' => $this->rentalOrder->getMovieId()->getValue(),
        ];
    }
}
