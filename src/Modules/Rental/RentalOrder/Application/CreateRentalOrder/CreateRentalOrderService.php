<?php

declare(strict_types=1);

namespace App\Modules\Rental\RentalOrder\Application\CreateRentalOrder;

use App\Modules\Rental\RentalOrder\Domain\RentalInterval;
use App\Modules\Rental\RentalOrder\Domain\RentalOrder;
use App\Modules\Rental\RentalOrder\Domain\RentalOrderRepositoryInterface;
use App\Modules\Rental\RentalOrder\Domain\RentalStatus;
use App\Modules\Rental\User\Domain\User;
use App\Modules\Rental\User\Domain\UserRepositoryInterface;
use App\Shared\Domain\Exception\EntityNotFoundException;
use App\Shared\Domain\Uuid;
use App\Shared\Movie\Domain\Exception\InsufficientStockException;
use App\Shared\Movie\Domain\Movie;
use App\Shared\Movie\Domain\MovieRepositoryInterface;

class CreateRentalOrderService
{
    private UserRepositoryInterface $userRepository;
    private MovieRepositoryInterface $movieRepository;
    private RentalOrderRepositoryInterface $rentalOrderRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        MovieRepositoryInterface $movieRepository,
        RentalOrderRepositoryInterface $rentalOrderRepository
    ) {
        $this->userRepository = $userRepository;
        $this->movieRepository = $movieRepository;
        $this->rentalOrderRepository = $rentalOrderRepository;
    }

    /**
     * @throws EntityNotFoundException|InsufficientStockException|MovieAlreadyRentedException
     */
    public function execute(CreateRentalOrderRequest $request): CreateRentalOrderResponse
    {
        $orderId = Uuid::create($request->getOrderId());
        $userId = Uuid::create($request->getUserId());
        $movieId = Uuid::create($request->getMovieId());

        $user = $this->getUser($userId);
        $movie = $this->getMovie($movieId);

        $this->ensureUniqueRentalOrderForMovie($userId, $movieId);

        $rentalInterval = new RentalInterval($request->getFrom(), $request->getTo());
        $status = new RentalStatus(RentalStatus::ACTIVE);
        $rentalOrder = new RentalOrder($orderId, $user, $movie, $rentalInterval, $status);
        $this->rentalOrderRepository->save($rentalOrder);

        return new CreateRentalOrderResponse($rentalOrder);
    }

    /**
     * @throws EntityNotFoundException
     */
    private function getUser(Uuid $userId): User
    {
        $user = $this->userRepository->getById($userId);
        if (!$user) {
            throw new EntityNotFoundException('User not found');
        }

        return $user;
    }

    /**
     * @throws EntityNotFoundException
     */
    private function getMovie(Uuid $movieId): Movie
    {
        $movie = $this->movieRepository->getById($movieId);
        if (!$movie) {
            throw new EntityNotFoundException('Movie not found');
        }

        return $movie;
    }

    /**
     * @throws MovieAlreadyRentedException
     */
    private function ensureUniqueRentalOrderForMovie(Uuid $userId, Uuid $movieId): void
    {
        $activeOrders = $this->rentalOrderRepository->getByUserIdAndMovieId($userId, $movieId);
        if (count($activeOrders) > 0) {
            throw new MovieAlreadyRentedException();
        }
    }
}
