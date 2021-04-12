<?php

declare(strict_types=1);

namespace App\Tests\Modules\Rental\RentalOrder\Application\CreateRentalOrder;

use App\Modules\Rental\RentalOrder\Application\CreateRentalOrder\CreateRentalOrderRequest;
use App\Modules\Rental\RentalOrder\Application\CreateRentalOrder\CreateRentalOrderService;
use App\Modules\Rental\RentalOrder\Application\CreateRentalOrder\MovieAlreadyRentedException;
use App\Modules\Rental\RentalOrder\Domain\Event\RentalOrderCreated;
use App\Modules\Rental\RentalOrder\Domain\RentalOrderRepositoryInterface;
use App\Modules\Rental\User\Domain\UserRepositoryInterface;
use App\Shared\Domain\Event\DomainEventDispatcher;
use App\Shared\Domain\Exception\EntityNotFoundException;
use App\Shared\Movie\Domain\Movie;
use App\Shared\Movie\Domain\MovieRepositoryInterface;
use App\Tests\Modules\Rental\User\UserTestsTrait;
use App\Tests\Shared\Movie\Application\MovieTestTrait;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateRentalOrderServiceTest extends WebTestCase
{
    use MovieTestTrait;
    use UserTestsTrait;

    /** @test */
    public function should_create_rental_order_given_correct_data()
    {
        $user = $this->getUser();
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('getById')->willReturn($user);

        $movie = $this->getMovie(1);
        $movieRepository = $this->createMock(MovieRepositoryInterface::class);
        $movieRepository->method('getById')->willReturn($movie);

        $rentalOrderRepository = $this->createMock(RentalOrderRepositoryInterface::class);

        $request = $this->getCorrectRequest($movie);
        $service = new CreateRentalOrderService($userRepository, $movieRepository, $rentalOrderRepository);
        $response = $service->execute($request);

        $this->assertEquals($movie->getId()->getValue(), $response->getData()['movieId']);
        $this->assertEquals($user->getId()->getValue(), $response->getData()['userId']);
    }

    /** @test */
    public function should_throw_entity_not_found_given_inexistent_user()
    {
        $user = $this->getUser();
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('getById')->willReturn($user);

        $movieRepository = $this->createMock(MovieRepositoryInterface::class);
        $movieRepository->method('getById')->willReturn(null);

        $rentalOrderRepository = $this->createMock(RentalOrderRepositoryInterface::class);

        $request = $this->getCorrectRequest($this->getMovie(1));
        $service = new CreateRentalOrderService($userRepository, $movieRepository, $rentalOrderRepository);

        $this->expectException(EntityNotFoundException::class);
        $service->execute($request);
    }

    /** @test */
    public function should_throw_entity_not_found_given_inexistent_movie()
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('getById')->willReturn(null);

        $movie = $this->getMovie(1);
        $movieRepository = $this->createMock(MovieRepositoryInterface::class);
        $movieRepository->method('getById')->willReturn($movie);

        $rentalOrderRepository = $this->createMock(RentalOrderRepositoryInterface::class);

        $request = $this->getCorrectRequest($movie);
        $service = new CreateRentalOrderService($userRepository, $movieRepository, $rentalOrderRepository);

        $this->expectException(EntityNotFoundException::class);
        $service->execute($request);
    }

    /** @test */
    public function should_throw_movie_already_rented_given_movie_rented_by_the_user()
    {
        $user = $this->getUser();
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('getById')->willReturn($user);

        $movie = $this->getMovie(1);
        $movieRepository = $this->createMock(MovieRepositoryInterface::class);
        $movieRepository->method('getById')->willReturn($movie);

        $rentalOrderRepository = $this->createMock(RentalOrderRepositoryInterface::class);
        $rentalOrderRepository->method('getByUserIdAndMovieId')->willReturn([1]);

        $request = $this->getCorrectRequest($movie);
        $service = new CreateRentalOrderService($userRepository, $movieRepository, $rentalOrderRepository);

        $this->expectException(MovieAlreadyRentedException::class);
        $service->execute($request);
    }

    /** @test */
    public function should_publish_rental_order_created()
    {
        $user = $this->getUser();
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('getById')->willReturn($user);

        $movie = $this->getMovie(1);
        $movieRepository = $this->createMock(MovieRepositoryInterface::class);
        $movieRepository->method('getById')->willReturn($movie);

        $rentalOrderRepository = $this->createMock(RentalOrderRepositoryInterface::class);

        DomainEventDispatcher::getInstance()->clearEvents();
        $request = $this->getCorrectRequest($movie);
        $service = new CreateRentalOrderService($userRepository, $movieRepository, $rentalOrderRepository);
        $service->execute($request);

        $this->assertInstanceOf(RentalOrderCreated::class, DomainEventDispatcher::getInstance()->getEvents()[0]);
    }

    private function getCorrectRequest(Movie $movie): CreateRentalOrderRequest
    {
        $request = new CreateRentalOrderRequest();
        $request->setOrderId(Uuid::uuid4()->toString());
        $request->setMovieId($movie->getId()->getValue());
        $request->setUserId(Uuid::uuid4()->toString());
        $request->setFrom(new \DateTimeImmutable());
        $request->setTo(new \DateTimeImmutable());

        return $request;
    }
}
