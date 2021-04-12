<?php


namespace App\Tests\Modules\Rental\RentalOrder;


use App\Modules\Rental\RentalOrder\Domain\RentalInterval;
use App\Modules\Rental\RentalOrder\Domain\RentalOrder;
use App\Modules\Rental\RentalOrder\Domain\RentalStatus;
use App\Modules\Rental\User\Domain\User;
use App\Shared\Domain\Uuid;
use App\Shared\Movie\Domain\Movie;

trait RentalOrderTestsTrait
{
    private function getOrder(User $user, Movie $movie, RentalStatus $status): RentalOrder
    {
        return new RentalOrder(
            Uuid::create(\Ramsey\Uuid\Uuid::uuid4()->toString()),
            $user,
            $movie,
            new RentalInterval(new \DateTimeImmutable(), new \DateTimeImmutable()),
            $status
        );
    }
}
