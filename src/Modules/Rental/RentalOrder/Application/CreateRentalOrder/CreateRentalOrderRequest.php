<?php

declare(strict_types=1);

namespace App\Modules\Rental\RentalOrder\Application\CreateRentalOrder;

use DateTimeImmutable;

class CreateRentalOrderRequest
{
    private string $orderId;
    private string $userId;
    private string $movieId;
    private DateTimeImmutable $from;
    private DateTimeImmutable $to;

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function getMovieId(): string
    {
        return $this->movieId;
    }

    public function setMovieId(string $movieId): void
    {
        $this->movieId = $movieId;
    }

    public function getFrom(): DateTimeImmutable
    {
        return $this->from;
    }

    public function setFrom(DateTimeImmutable $from): void
    {
        $this->from = $from;
    }

    public function getTo(): DateTimeImmutable
    {
        return $this->to;
    }

    public function setTo(DateTimeImmutable $to): void
    {
        $this->to = $to;
    }
}
