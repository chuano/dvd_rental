<?php

declare(strict_types=1);

namespace App\Framework\Controller\API\Movies;

use App\Framework\Form\CreateMovieType;
use App\Framework\Form\UpdateMovieType;
use App\Framework\Security\JwtAuthenticatedInterface;
use App\Framework\Security\LoggedUserService;
use App\Shared\Domain\Credentials;
use App\Shared\Domain\Exception\ForbbidenException;
use App\Shared\Movie\Application\CreateMovie\CreateMovieRequest;
use App\Shared\Movie\Application\CreateMovie\CreateMovieService;
use App\Shared\Movie\Application\UpdateMovie\UpdateMovieRequest;
use App\Shared\Movie\Application\UpdateMovie\UpdateMovieService;
use App\Shared\Movie\Infra\MovieRepositoryImpl;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UpdateMovieController extends AbstractController implements JwtAuthenticatedInterface
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
     * @Route("/api/movies/{movieId}", methods={"PATCH", "PUT"})
     */
    public function createOrder(Request $request, string $movieId): Response
    {
        $userCredentials = $this->loggedUserService->getCredentials($request);
        $data = json_decode($request->getContent(), true);

        $updateMovieRequest = new UpdateMovieRequest();
        $updateMovieRequest->setUserProfile($userCredentials->getProfile());
        $updateMovieRequest->setId($movieId);
        $form = $this->createForm(UpdateMovieType::class, $updateMovieRequest);
        $form->submit($data);

        $createMovieService = new UpdateMovieService($this->movieRepository);
        $response = $createMovieService->execute($updateMovieRequest);

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($response->getData(), 'json'),
            Response::HTTP_OK
        );
    }
}
