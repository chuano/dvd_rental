<?php

declare(strict_types=1);

namespace App\Tests\Modules\Rental\RentalOrder\Domain;

use App\Modules\Rental\RentalOrder\Domain\Event\RentalOrderCreated;
use App\Modules\Rental\RentalOrder\Domain\Exception\InvalidRentalOrderStatusException;
use App\Modules\Rental\RentalOrder\Domain\RentalInterval;
use App\Modules\Rental\RentalOrder\Domain\RentalOrder;
use App\Modules\Rental\RentalOrder\Domain\RentalStatus;
use App\Shared\Domain\Event\DomainEventDispatcher;
use App\Shared\Domain\Exception\ForbiddenException;
use App\Shared\Domain\Uuid;
use App\Shared\Movie\Domain\Exception\InsufficientStockException;
use App\Tests\Modules\Rental\RentalOrder\RentalOrderTestsTrait;
use App\Tests\Modules\Rental\User\UserTestsTrait;
use App\Tests\Shared\Movie\Application\MovieTestTrait;
use PHPUnit\Framework\TestCase;

class RentalOrderTest extends TestCase
{
    use UserTestsTrait;
    use MovieTestTrait;
    use RentalOrderTestsTrait;

    /** @test */
    public function should_throw_error_given_movie_whithout_stock()
    {
        $movie = $this->getMovie(0);
        $user = $this->getUser();
        $this->expectException(InsufficientStockException::class);
        new RentalOrder(
            Uuid::create(\Ramsey\Uuid\Uuid::uuid4()->toString()),
            $user,
            $movie,
            new RentalInterval(new \DateTimeImmutable(), new \DateTimeImmutable()),
            new RentalStatus(RentalStatus::ACTIVE)
        );
    }

    /** @test */
    public function should_pulish_rental_order_create_event()
    {
        $movie = $this->getMovie(1);
        $user = $this->getUser();
        DomainEventDispatcher::getInstance()->clearEvents();
        new RentalOrder(
            Uuid::create(\Ramsey\Uuid\Uuid::uuid4()->toString()),
            $user,
            $movie,
            new RentalInterval(new \DateTimeImmutable(), new \DateTimeImmutable()),
            new RentalStatus(RentalStatus::ACTIVE)
        );
        $this->assertInstanceOf(RentalOrderCreated::class, DomainEventDispatcher::getInstance()->getEvents()[0]);
    }

    /** @test */
    public function should_throw_error_when_finish_with_different_user()
    {
        $movie = $this->getMovie(1);
        $user = $this->getUser();
        $otherUser = $this->getUser();
        $order = $this->getOrder($user, $movie, new RentalStatus(RentalStatus::ACTIVE));
        $this->expectException(ForbiddenException::class);
        $order->finish($otherUser->getId());
    }

    /** @test */
    public function should_throw_error_when_finish_already_finished_order()
    {
        $movie = $this->getMovie(1);
        $user = $this->getUser();
        $order = $this->getOrder($user, $movie, new RentalStatus(RentalStatus::DONE));
        $this->expectException(InvalidRentalOrderStatusException::class);
        $order->finish($user->getId());
    }
}
