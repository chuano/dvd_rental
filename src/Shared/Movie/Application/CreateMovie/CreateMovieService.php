<?php

declare(strict_types=1);

namespace App\Shared\Movie\Application\CreateMovie;

use App\Shared\Domain\Credentials;
use App\Shared\Domain\Exception\ForbbidenException;
use App\Shared\Domain\Uuid;
use App\Shared\Movie\Domain\Movie;
use App\Shared\Movie\Domain\MovieMetadata;
use App\Shared\Movie\Domain\MovieRepositoryInterface;

class CreateMovieService
{
    private MovieRepositoryInterface $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    /**
     * @throws ForbbidenException
     */
    public function execute(CreateMovieRequest $request): CreateMovieResponse
    {
        if ($request->getUserProfile() !== Credentials::ADMIN_PROFILE) {
            throw new ForbbidenException();
        }
        $id = Uuid::create($request->getUuid());
        $metadata = new MovieMetadata($request->getTitle(), $request->getYear(), $request->getSynopsis());
        $movie = new Movie($id, $metadata, $request->getStock());
        $this->movieRepository->save($movie);

        return new CreateMovieResponse($movie);
    }
}
