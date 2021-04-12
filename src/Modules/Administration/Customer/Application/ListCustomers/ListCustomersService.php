<?php

declare(strict_types=1);

namespace App\Modules\Administration\Customer\Application\ListCustomers;

use App\Modules\Administration\Customer\Domain\CustomerRepositoryInterface;
use App\Shared\Domain\Credentials;
use App\Shared\Domain\Exception\ForbiddenException;

class ListCustomersService
{
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @throws ForbiddenException
     */
    public function execute(ListCustomersRequest $request): ListCustomersResponse
    {
        if ($request->getUserProfile() !== Credentials::ADMIN_PROFILE) {
            throw new ForbiddenException();
        }
        $total = $this->customerRepository->count();
        $customers = $this->customerRepository->getAll($request->getPage(), $request->getLimit());

        return new ListCustomersResponse($request->getPage(), $request->getLimit(), $total, $customers);
    }
}
