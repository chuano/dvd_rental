<?php

declare(strict_types=1);

namespace App\Tests\Shared\Movie\Application\UpdateMovie;

use App\Shared\Domain\Credentials;
use App\Shared\Domain\Event\DomainEventDispatcher;
use App\Shared\Domain\Exception\EntityNotFoundException;
use App\Shared\Domain\Exception\ForbbidenException;
use App\Shared\Movie\Application\UpdateMovie\UpdateMovieRequest;
use App\Shared\Movie\Application\UpdateMovie\UpdateMovieService;
use App\Shared\Movie\Domain\Event\MovieUpdated;
use App\Shared\Movie\Domain\MovieRepositoryInterface;
use App\Tests\Shared\Movie\Application\MovieTestTrait;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateMovieServiceTest extends WebTestCase
{
    use MovieTestTrait;

    /** @test */
    public function should_update_movie_given_correct_data()
    {
        $movie = $this->getMovie(1);
        $repository = $this->createMock(MovieRepositoryInterface::class);
        $repository->method('getById')->willReturn($movie);

        $request = $this->getCorrectRequest($movie->getId()->getValue());
        $service = new UpdateMovieService($repository);
        $service->execute($request);

        $this->assertEquals($request->getTitle(), $movie->getMetadata()->getTitle());
        $this->assertEquals($request->getYear(), $movie->getMetadata()->getYear());
        $this->assertEquals($request->getSynopsis(), $movie->getMetadata()->getSynopsis());
        $this->assertEquals($request->getStock(), $movie->getStock());
    }

    /** @test */
    public function should_throw_forbbiden_error_given_non_admin_user()
    {
        $repository = $this->createMock(MovieRepositoryInterface::class);

        $request = $this->getCorrectRequest('');
        $request->setUserProfile(Credentials::USER_PROFILE);
        $service = new UpdateMovieService($repository);
        $this->expectException(ForbbidenException::class);
        $service->execute($request);
    }

    /** @test */
    public function should_throw_entity_not_found_given_inexistent_movie()
    {
        $repository = $this->createMock(MovieRepositoryInterface::class);
        $repository->method('getById')->willReturn(null);

        $request = $this->getCorrectRequest('');
        $service = new UpdateMovieService($repository);
        $this->expectException(EntityNotFoundException::class);
        $service->execute($request);
    }

    /** @test */
    public function should_publis_movie_updated_event()
    {
        DomainEventDispatcher::getInstance()->clearEvents();
        $movie = $this->getMovie(1);
        $repository = $this->createMock(MovieRepositoryInterface::class);
        $repository->method('getById')->willReturn($movie);

        $request = $this->getCorrectRequest($movie->getId()->getValue());
        $service = new UpdateMovieService($repository);
        $service->execute($request);

        $this->assertInstanceOf(MovieUpdated::class, DomainEventDispatcher::getInstance()->getEvents()[0]);
    }

    private function getCorrectRequest(string $movieId): UpdateMovieRequest
    {
        $movieId = Uuid::uuid4()->toString();
        $request = new UpdateMovieRequest();
        $request->setId($movieId);
        $request->setUserProfile(Credentials::ADMIN_PROFILE);
        $request->setTitle('Kill Bill II');
        $request->setSynopsis('Tras eliminar a algunos miembros de la banda que intentaron asesinarla el día...');
        $request->setYear(2004);
        $request->setStock(1);

        return $request;
    }
}
