<?php

namespace App\Modules\Rental\RentalOrder\Domain;

use App\Shared\Domain\Uuid;

interface RentalOrderRepositoryInterface
{
    public function save(RentalOrder $rentalOrder): void;

    public function getById(Uuid $id): ?RentalOrder;

    public function getByUserId(Uuid $userId): array;

    public function getByUserIdAndMovieId(Uuid $userId, Uuid $movieId): array;
}
