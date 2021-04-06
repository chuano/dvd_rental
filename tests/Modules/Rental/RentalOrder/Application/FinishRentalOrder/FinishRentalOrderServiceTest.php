<?php

declare(strict_types=1);

namespace App\Tests\Modules\Rental\RentalOrder\Application\FinishRentalOrder;

use App\Modules\Rental\RentalOrder\Application\FinishRentalOrder\FinishRentalOrderRequest;
use App\Modules\Rental\RentalOrder\Application\FinishRentalOrder\FinishRentalOrderService;
use App\Modules\Rental\RentalOrder\Domain\Event\RentalOrderFinished;
use App\Modules\Rental\RentalOrder\Domain\Exception\InvalidRentalOrderStatusException;
use App\Modules\Rental\RentalOrder\Domain\RentalOrderRepositoryInterface;
use App\Modules\Rental\RentalOrder\Domain\RentalStatus;
use App\Modules\Rental\User\Domain\User;
use App\Shared\Domain\Event\DomainEventDispatcher;
use App\Shared\Domain\Exception\ForbbidenException;
use App\Tests\Modules\Rental\RentalOrder\RentalOrderTestsTrait;
use App\Tests\Modules\Rental\User\UserTestsTrait;
use App\Tests\Shared\Movie\Application\MovieTestTrait;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FinishRentalOrderServiceTest extends WebTestCase
{
    use UserTestsTrait;
    use RentalOrderTestsTrait;
    use MovieTestTrait;

    /** @test */
    public function should_finish_rental_order_given_correct_data()
    {
        $user = $this->getUser();
        $movie = $this->getMovie(1);
        $order = $this->getOrder($user, $movie, new RentalStatus(RentalStatus::ACTIVE));
        $rentalOrderRepository = $this->createMock(RentalOrderRepositoryInterface::class);
        $rentalOrderRepository->method('getById')->willReturn($order);

        $request = $this->getCorrectRequest($user);
        $service = new FinishRentalOrderService($rentalOrderRepository);
        $response = $service->execute($request);

        $this->assertEquals('DONE', $response->getData()['status']);
    }

    /** @test */
    public function sholud_throw_forbbiden_exception_given_other_user_than_rental_user()
    {
        $user = $this->getUser();
        $other = $this->getUser();
        $movie = $this->getMovie(1);
        $order = $this->getOrder($user, $movie, new RentalStatus(RentalStatus::ACTIVE));
        $rentalOrderRepository = $this->createMock(RentalOrderRepositoryInterface::class);
        $rentalOrderRepository->method('getById')->willReturn($order);

        $request = $this->getCorrectRequest($other);
        $service = new FinishRentalOrderService($rentalOrderRepository);

        $this->expectException(ForbbidenException::class);
        $service->execute($request);
    }

    /** @test */
    public function sholud_throw_invalid_status_exception_givenrental_in_done_status()
    {
        $user = $this->getUser();
        $movie = $this->getMovie(1);
        $order = $this->getOrder($user, $movie, new RentalStatus(RentalStatus::DONE));
        $rentalOrderRepository = $this->createMock(RentalOrderRepositoryInterface::class);
        $rentalOrderRepository->method('getById')->willReturn($order);

        $request = $this->getCorrectRequest($user);
        $service = new FinishRentalOrderService($rentalOrderRepository);

        $this->expectException(InvalidRentalOrderStatusException::class);
        $service->execute($request);
    }

    /** @test */
    public function should_publish_rental_order_finished_event_when_set_done_rental()
    {
        $user = $this->getUser();
        $movie = $this->getMovie(1);
        $order = $this->getOrder($user, $movie, new RentalStatus(RentalStatus::ACTIVE));
        $rentalOrderRepository = $this->createMock(RentalOrderRepositoryInterface::class);
        $rentalOrderRepository->method('getById')->willReturn($order);

        DomainEventDispatcher::getInstance()->clearEvents();
        $request = $this->getCorrectRequest($user);
        $service = new FinishRentalOrderService($rentalOrderRepository);
        $service->execute($request);

        $this->assertInstanceOf(RentalOrderFinished::class, DomainEventDispatcher::getInstance()->getEvents()[0]);
    }

    private function getCorrectRequest(User $user): FinishRentalOrderRequest
    {
        return new FinishRentalOrderRequest(Uuid::uuid4()->toString(), $user->getId()->getValue());
    }
}
