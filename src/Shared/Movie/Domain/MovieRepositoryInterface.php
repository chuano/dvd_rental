<?php

declare(strict_types=1);

namespace App\Shared\Movie\Domain;

use App\Shared\Domain\Uuid;

interface MovieRepositoryInterface
{
    public function save(Movie $movie): void;

    public function getById(Uuid $id): ?Movie;

    /**
     * @return Movie[]
     */
    public function getAll(int $page, int $limit): array;

    public function count(): int;

    public function delete(Uuid $id): void;
}
