<?php

declare(strict_types=1);

namespace App\Framework\Controller\API\Movies;

use App\Framework\Form\CreateMovieType;
use App\Framework\Security\JwtAuthenticatedInterface;
use App\Framework\Security\LoggedUserService;
use App\Shared\Domain\Credentials;
use App\Shared\Domain\Exception\ForbbidenException;
use App\Shared\Movie\Application\CreateMovie\CreateMovieRequest;
use App\Shared\Movie\Application\CreateMovie\CreateMovieService;
use App\Shared\Movie\Application\DeleteMovie\DeleteMovieRequest;
use App\Shared\Movie\Application\DeleteMovie\DeleteMovieService;
use App\Shared\Movie\Infra\MovieRepositoryImpl;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DeleteMovieController extends AbstractController implements JwtAuthenticatedInterface
{
    private MovieRepositoryImpl $movieRepository;
    private SerializerInterface $serializer;
    private LoggedUserService $loggedUserService;

    public function __construct(
        MovieRepositoryImpl $movieRepository,
        LoggedUserService $loggedUserService,
        SerializerInterface $serializer
    ) {
        $this->movieRepository = $movieRepository;
        $this->loggedUserService = $loggedUserService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/movies/{movieId}", methods={"DELETE"})
     */
    public function deleteMovie(Request $request, string $movieId): Response
    {
        $userCredentials = $this->loggedUserService->getCredentials($request);
        $deleteMovieRequest = new DeleteMovieRequest();
        $deleteMovieRequest->setUserProfile($userCredentials->getProfile());
        $deleteMovieRequest->setId($movieId);

        $deleteMovieService = new DeleteMovieService($this->movieRepository);
        $deleteMovieService->execute($deleteMovieRequest);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
