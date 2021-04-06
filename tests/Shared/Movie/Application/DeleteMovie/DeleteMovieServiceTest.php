<?php

declare(strict_types=1);

namespace App\Tests\Shared\Movie\Application\DeleteMovie;

use App\Shared\Domain\Credentials;
use App\Shared\Domain\Event\DomainEventDispatcher;
use App\Shared\Domain\Exception\EntityNotFoundException;
use App\Shared\Domain\Exception\ForbbidenException;
use App\Shared\Movie\Application\DeleteMovie\DeleteMovieRequest;
use App\Shared\Movie\Application\DeleteMovie\DeleteMovieService;
use App\Shared\Movie\Domain\MovieRepositoryInterface;
use App\Tests\Shared\Movie\Application\MovieTestTrait;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteMovieServiceTest extends WebTestCase
{
    use MovieTestTrait;

    /** @test */
    public function should_delete_movie_given_correct_data()
    {
        $repository = $this->createMock(MovieRepositoryInterface::class);
        $repository->method('getById')->willReturn($this->getMovie(1));
        $repository->expects($this->exactly(1))->method('delete');

        $request = $this->getCorrectRequest();
        $service = new DeleteMovieService($repository);
        $service->execute($request);
    }

    /** @test */
    public function should_throw_forbbiden_error_given_non_admin_user()
    {
        $repository = $this->createMock(MovieRepositoryInterface::class);

        $request = $this->getCorrectRequest();
        $request->setUserProfile(Credentials::USER_PROFILE);
        $service = new DeleteMovieService($repository);
        $this->expectException(ForbbidenException::class);
        $service->execute($request);
    }

    /** @test */
    public function should_throw_entity_not_found_given_inexistent_movie()
    {
        $repository = $this->createMock(MovieRepositoryInterface::class);
        $repository->method('getById')->willReturn(null);

        $request = $this->getCorrectRequest();
        $service = new DeleteMovieService($repository);
        $this->expectException(EntityNotFoundException::class);
        $service->execute($request);
    }

    private function getCorrectRequest(): DeleteMovieRequest
    {
        $movieId = Uuid::uuid4()->toString();
        $request = new DeleteMovieRequest();
        $request->setId($movieId);
        $request->setUserProfile(Credentials::ADMIN_PROFILE);

        return $request;
    }
}
