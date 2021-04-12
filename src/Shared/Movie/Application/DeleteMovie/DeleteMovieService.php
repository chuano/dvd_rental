<?php

declare(strict_types=1);

namespace App\Shared\Movie\Application\DeleteMovie;

use App\Shared\Domain\Credentials;
use App\Shared\Domain\Exception\EntityNotFoundException;
use App\Shared\Domain\Exception\ForbiddenException;
use App\Shared\Domain\Uuid;
use App\Shared\Movie\Domain\MovieRepositoryInterface;

class DeleteMovieService
{
    private MovieRepositoryInterface $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    /**
     * @throws ForbiddenException|EntityNotFoundException
     */
    public function execute(DeleteMovieRequest $request): void
    {
        if ($request->getUserProfile() !== Credentials::ADMIN_PROFILE) {
            throw new ForbiddenException();
        }

        $movieId = Uuid::create($request->getId());
        $movie = $this->movieRepository->getById($movieId);
        if (!$movie) {
            throw new EntityNotFoundException('Movie not found');
        }

        $this->movieRepository->delete($movieId);
    }
}
