<?php

declare(strict_types=1);

namespace App\Modules\Rental\RentalOrder\Application\UserRentalOrders;

use App\Modules\Rental\RentalOrder\Domain\RentalOrderRepositoryInterface;
use App\Shared\Domain\Uuid;

class UserRentalOrdersService
{
    private RentalOrderRepositoryInterface $rentalOrderRepository;

    public function __construct(RentalOrderRepositoryInterface $rentalOrderRepository)
    {
        $this->rentalOrderRepository = $rentalOrderRepository;
    }

    public function execute(UserRentalOrdersRequest $request): UserRentalOrdersResponse
    {
        $userId = Uuid::create($request->getUserId());
        return new UserRentalOrdersResponse($this->rentalOrderRepository->getByUserId($userId));
    }
}
