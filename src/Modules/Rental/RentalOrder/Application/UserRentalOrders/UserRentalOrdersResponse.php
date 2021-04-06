<?php

declare(strict_types=1);

namespace App\Modules\Rental\RentalOrder\Application\UserRentalOrders;

use App\Modules\Rental\RentalOrder\Domain\RentalOrder;

class UserRentalOrdersResponse
{
    private array $rentalOrders;

    public function __construct(array $rentalOrders)
    {
        $this->rentalOrders = $rentalOrders;
    }

    public function getData(): array
    {
        return [
            'data' => array_map(
                fn(RentalOrder $rentalOrder) => [
                    'id' => $rentalOrder->getId()->getValue(),
                    'movieId' => $rentalOrder->getMovieId()->getValue(),
                ],
                $this->rentalOrders
            )
        ];
    }
}
