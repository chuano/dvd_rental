<?php

declare(strict_types=1);

namespace App\Framework\Controller\API\Movies;

use App\Framework\Security\JwtAuthenticatedInterface;
use App\Shared\Movie\Application\ListMovies\ListMoviesRequest;
use App\Shared\Movie\Application\ListMovies\ListMoviesService;
use App\Shared\Movie\Infra\MovieRepositoryImpl;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ListMoviesController implements JwtAuthenticatedInterface
{
    private MovieRepositoryImpl $movieRepository;
    private SerializerInterface $serializer;

    public function __construct(MovieRepositoryImpl $movieRepository, SerializerInterface $serializer)
    {
        $this->movieRepository = $movieRepository;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/movies", methods={"GET"})
     */
    public function listMwovies(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $listMoviesRequest = new ListMoviesRequest($page, $limit);
        $listMoviesService = new ListMoviesService($this->movieRepository);
        $response = $listMoviesService->execute($listMoviesRequest);

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($response->getData(), 'json'),
            Response::HTTP_OK
        );
    }
}
