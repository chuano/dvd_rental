<?php

declare(strict_types=1);

namespace App\Modules\Administration\Sale\Application\ListSales;

use App\Modules\Administration\Sale\Domain\SaleRepositoryInterface;
use App\Shared\Domain\Credentials;
use App\Shared\Domain\Exception\ForbiddenException;

class ListSalesService
{
    private SaleRepositoryInterface $saleRepository;

    public function __construct(SaleRepositoryInterface $saleRepository)
    {
        $this->saleRepository = $saleRepository;
    }

    public function execute(ListSalesRequest $request): ListSalesResponse
    {
        if ($request->getUserProfile() !== Credentials::ADMIN_PROFILE) {
            throw new ForbiddenException();
        }
        $total = $this->saleRepository->count();
        $sales = $this->saleRepository->getAll($request->getPage(), $request->getLimit());

        return new ListSalesResponse($request->getPage(), $request->getLimit(), $total, $sales);
    }
}
