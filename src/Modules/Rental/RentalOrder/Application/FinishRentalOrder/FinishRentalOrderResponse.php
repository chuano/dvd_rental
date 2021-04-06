<?php

declare(strict_types=1);

namespace App\Modules\Rental\RentalOrder\Application\FinishRentalOrder;

use App\Modules\Rental\RentalOrder\Domain\RentalOrder;

class FinishRentalOrderResponse
{
    private RentalOrder $rentalOrder;

    public function __construct(RentalOrder $rentalOrder)
    {
        $this->rentalOrder = $rentalOrder;
    }

    public function getData()
    {
        return [
            'id' => $this->rentalOrder->getId()->getValue(),
            'status' => $this->rentalOrder->getStatus()->getStatus(),
        ];
    }
}
