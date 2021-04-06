<?php

declare(strict_types=1);

namespace App\Modules\Administration\Sale\Application\ListSales;

use App\Modules\Administration\Sale\Domain\Sale;

class ListSalesResponse
{
    private int $page;
    private int $limit;
    private int $total;
    private array $sales;

    public function __construct(int $page, int $limit, int $total, array $sales)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->total = $total;
        $this->sales = $sales;
    }

    public function getData(): array
    {
        return [
            'page' => $this->page,
            'limit' => $this->limit,
            'total' => $this->total,
            'data' => array_map(
                fn(Sale $sale) => [
                    'id' => $sale->getId()->getValue(),
                    'movie' => $sale->getTitle()->getValue(),
                    'customer' => $sale->getCompleteName()->__toString(),
                    'date' => $sale->getDate()->format(DATE_ISO8601),
                ],
                $this->sales
            ),
        ];
    }
}
