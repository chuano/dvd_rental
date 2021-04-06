<?php

namespace App\Modules\Administration\Customer\Domain;


use App\Shared\Domain\Uuid;

interface CustomerRepositoryInterface
{
    public function save(Customer $customer): void;

    public function getById(Uuid $id): ?Customer;

    public function getAll(int $page, int $limit): array;

    public function count(): int;
}
