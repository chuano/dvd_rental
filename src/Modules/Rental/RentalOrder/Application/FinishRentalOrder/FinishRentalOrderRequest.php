<?php

declare(strict_types=1);

namespace App\Modules\Rental\RentalOrder\Application\FinishRentalOrder;

class FinishRentalOrderRequest
{
    private string $orderId;
    private string $userId;

    public function __construct(string $orderId, string $userId)
    {
        $this->orderId = $orderId;
        $this->userId = $userId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
