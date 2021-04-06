<?php

declare(strict_types=1);

namespace App\Tests\Shared\Movie\Application\ListMovies;

use App\Shared\Movie\Application\ListMovies\ListMoviesRequest;
use App\Shared\Movie\Application\ListMovies\ListMoviesService;
use App\Shared\Movie\Domain\MovieRepositoryInterface;
use App\Tests\Shared\Movie\Application\MovieTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListMoviesServiceTest extends WebTestCase
{
    use MovieTestTrait;

    /** @test */
    public function should_list_movies()
    {
        $page = 1;
        $limit = 10;
        $total = 100;
        $repository = $this->createMock(MovieRepositoryInterface::class);
        $repository->method('getAll')->willReturn($this->getMovies($limit));
        $repository->method('count')->willReturn($total);


        $request = new ListMoviesRequest($page, $limit);
        $service = new ListMoviesService($repository);
        $response = $service->execute($request);

        $this->assertEquals($page, $response->getData()['page']);
        $this->assertEquals($limit, $response->getData()['limit']);
        $this->assertEquals($total, $response->getData()['total']);
        $this->assertCount($limit, $response->getData()['data']);
    }

    private function getMovies(int $limit): array
    {
        $movies = [];
        while (count($movies) < $limit) {
            $movies[] = $this->getMovie(1);
        }
        return $movies;
    }
}
