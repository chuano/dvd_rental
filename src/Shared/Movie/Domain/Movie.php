<?php

declare(strict_types=1);

namespace App\Shared\Movie\Domain;

use App\Shared\Domain\Event\DomainEventDispatcher;
use App\Shared\Domain\Uuid;
use App\Shared\Movie\Domain\Event\MovieUpdated;
use App\Shared\Movie\Domain\Exception\InsufficientStockException;

class Movie
{
    private Uuid $id;
    private MovieMetadata $metadata;
    private int $stock;

    public function __construct(Uuid $id, MovieMetadata $metadata, int $stock)
    {
        $this->id = $id;
        $this->metadata = $metadata;
        $this->stock = $stock;
    }

    public function update(MovieMetadata $metadata, int $stock): void
    {
        $this->metadata = $metadata;
        $this->stock = $stock;
        DomainEventDispatcher::getInstance()->publish(new MovieUpdated($this));
    }

    /**
     * @throws InsufficientStockException
     */
    public function decreaseStock(): void
    {
        if ($this->stock < 1) {
            throw new InsufficientStockException();
        }
        $this->stock--;
    }

    public function increaseStock(): void
    {
        $this->stock++;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getMetadata(): MovieMetadata
    {
        return $this->metadata;
    }

    public function getStock(): int
    {
        return $this->stock;
    }
}
