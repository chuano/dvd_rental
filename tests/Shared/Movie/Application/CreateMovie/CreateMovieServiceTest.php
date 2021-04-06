<?php

declare(strict_types=1);

namespace App\Tests\Shared\Movie\Application\CreateMovie;

use App\Shared\Domain\Credentials;
use App\Shared\Domain\Exception\ForbbidenException;
use App\Shared\Movie\Application\CreateMovie\CreateMovieRequest;
use App\Shared\Movie\Application\CreateMovie\CreateMovieService;
use App\Shared\Movie\Domain\MovieRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateMovieServiceTest extends WebTestCase
{
    /** @test */
    public function should_call_repository_save_method()
    {
        $repository = $this->createMock(MovieRepositoryInterface::class);
        $repository->expects($this->exactly(1))->method('save');

        $request = $this->getCorrectRequest();
        $service = new CreateMovieService($repository);
        $service->execute($request);
    }

    /** @test */
    public function should_create_a_movie_given_correct_data()
    {
        $repository = $this->createMock(MovieRepositoryInterface::class);

        $request = $this->getCorrectRequest();
        $service = new CreateMovieService($repository);
        $response = $service->execute($request);

        $this->assertEquals($response->getData()['id'], $request->getUuid());
    }

    /** @test */
    public function should_thrown_forbbiden_exception_given_non_admin_user()
    {
        $repository = $this->createMock(MovieRepositoryInterface::class);

        $request = $this->getCorrectRequest();
        $request->setUserProfile(Credentials::USER_PROFILE);
        $service = new CreateMovieService($repository);
        $this->expectException(ForbbidenException::class);
        $service->execute($request);
    }

    private function getCorrectRequest(): CreateMovieRequest
    {
        $movieId = Uuid::uuid4()->toString();
        $request = new CreateMovieRequest();
        $request->setUuid($movieId);
        $request->setUserProfile(Credentials::ADMIN_PROFILE);
        $request->setTitle('Kill Bill II');
        $request->setSynopsis('Tras eliminar a algunos miembros de la banda que intentaron asesinarla el día...');
        $request->setYear(2004);
        $request->setStock(1);

        return $request;
    }
}
