<?php

declare(strict_types=1);

namespace App\Modules\Rental\RentalOrder\Domain;

use App\Modules\Rental\RentalOrder\Domain\Event\RentalOrderCreated;
use App\Modules\Rental\RentalOrder\Domain\Event\RentalOrderFinished;
use App\Modules\Rental\RentalOrder\Domain\Exception\InvalidRentalOrderStatusException;
use App\Modules\Rental\User\Domain\User;
use App\Shared\Domain\Event\DomainEventDispatcher;
use App\Shared\Domain\Exception\ForbbidenException;
use App\Shared\Domain\Uuid;
use App\Shared\Movie\Domain\Exception\InsufficientStockException;
use App\Shared\Movie\Domain\Movie;

class RentalOrder
{
    private Uuid $id;
    private Uuid $userId;
    private Uuid $movieId;
    private RentalInterval $interval;
    private RentalStatus $status;

    /**
     * @throws InsufficientStockException
     */
    public function __construct(
        Uuid $id,
        User $user,
        Movie $movie,
        RentalInterval $interval,
        RentalStatus $status
    ) {
        $this->ensureMovieStock($movie);

        $this->id = $id;
        $this->userId = $user->getId();
        $this->movieId = $movie->getId();
        $this->interval = $interval;
        $this->status = $status;

        DomainEventDispatcher::getInstance()->publish(new RentalOrderCreated($this));
    }

    /**
     * @throws ForbbidenException|InvalidRentalOrderStatusException
     */
    public function finish(Uuid $userId): void
    {
        if (!$this->userId->equals($userId)) {
            throw new ForbbidenException();
        }
        if ($this->status->getStatus() === RentalStatus::DONE) {
            throw new InvalidRentalOrderStatusException();
        }

        $this->status = new RentalStatus(RentalStatus::DONE);

        DomainEventDispatcher::getInstance()->publish(new RentalOrderFinished($this));
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUserId(): Uuid
    {
        return $this->userId;
    }

    public function getMovieId(): Uuid
    {
        return $this->movieId;
    }

    public function getInterval(): RentalInterval
    {
        return $this->interval;
    }

    public function getStatus(): RentalStatus
    {
        return $this->status;
    }

    /**
     * @throws InsufficientStockException
     */
    private function ensureMovieStock(Movie $movie): void
    {
        if ($movie->getStock() < 1) {
            throw new InsufficientStockException();
        }
    }
}
