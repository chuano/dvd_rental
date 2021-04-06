<?php

namespace App\Modules\Administration\Sale\Domain;

interface SaleRepositoryInterface
{
    public function save(Sale $sale): void;

    public function getAll(int $page, int $limit): array;

    public function count(): int;
}
