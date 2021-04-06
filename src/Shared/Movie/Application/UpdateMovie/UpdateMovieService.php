<?php

declare(strict_types=1);

namespace App\Shared\Movie\Application\UpdateMovie;

use App\Shared\Domain\Credentials;
use App\Shared\Domain\Exception\EntityNotFoundException;
use App\Shared\Domain\Exception\ForbbidenException;
use App\Shared\Domain\Uuid;
use App\Shared\Movie\Domain\MovieMetadata;
use App\Shared\Movie\Domain\MovieRepositoryInterface;

class UpdateMovieService
{
    private MovieRepositoryInterface $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    /**
     * @throws EntityNotFoundException|ForbbidenException
     */
    public function execute(UpdateMovieRequest $request): UpdateMovieResponse
    {
        if ($request->getUserProfile() !== Credentials::ADMIN_PROFILE) {
            throw new ForbbidenException();
        }

        $movie = $this->movieRepository->getById(Uuid::create($request->getId()));
        if (!$movie) {
            throw new EntityNotFoundException('Movie not found');
        }

        $newMetadata = new MovieMetadata($request->getTitle(), $request->getYear(), $request->getSynopsis());
        $movie->update($newMetadata, $request->getStock());
        $this->movieRepository->save($movie);

        return new UpdateMovieResponse($movie);
    }
}
