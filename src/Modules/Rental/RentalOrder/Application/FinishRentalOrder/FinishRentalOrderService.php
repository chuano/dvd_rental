<?php

declare(strict_types=1);

namespace App\Modules\Rental\RentalOrder\Application\FinishRentalOrder;

use App\Modules\Rental\RentalOrder\Domain\Exception\InvalidRentalOrderStatusException;
use App\Modules\Rental\RentalOrder\Domain\RentalOrderRepositoryInterface;
use App\Shared\Domain\Exception\ForbbidenException;
use App\Shared\Domain\Uuid;

class FinishRentalOrderService
{
    private RentalOrderRepositoryInterface $rentalOrderRepository;

    public function __construct(RentalOrderRepositoryInterface $rentalOrderRepository)
    {
        $this->rentalOrderRepository = $rentalOrderRepository;
    }

    /**
     * @throws ForbbidenException|InvalidRentalOrderStatusException
     */
    public function execute(FinishRentalOrderRequest $request): FinishRentalOrderResponse
    {
        $userId = Uuid::create($request->getUserId());
        $orderId = Uuid::create($request->getOrderId());
        $rentalOrder = $this->rentalOrderRepository->getById($orderId);
        $rentalOrder->finish($userId);
        $this->rentalOrderRepository->save($rentalOrder);

        return new FinishRentalOrderResponse($rentalOrder);
    }
}
