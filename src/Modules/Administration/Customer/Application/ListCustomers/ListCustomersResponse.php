<?php

declare(strict_types=1);

namespace App\Modules\Administration\Customer\Application\ListCustomers;

use App\Modules\Administration\Customer\Domain\Customer;

class ListCustomersResponse
{
    private int $page;
    private int $limit;
    private int $total;
    private array $customers;

    public function __construct(int $page, int $limit, int $total, array $customers)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->total = $total;
        $this->customers = $customers;
    }

    public function getData(): array
    {
        return [
            'page' => $this->page,
            'limit' => $this->limit,
            'total' => $this->total,
            'data' => array_map(fn (Customer $customer) =>  [
                'id' => $customer->getId()->getValue(),
                'name' => $customer->getCompleteName()->__toString(),
            ], $this->customers),
        ];
    }
}
