<?php

declare(strict_types=1);

namespace App\Framework\Controller\API\Movies;

use App\Framework\Form\CreateMovieType;
use App\Framework\Security\JwtAuthenticatedInterface;
use App\Framework\Security\LoggedUserService;
use App\Shared\Movie\Application\CreateMovie\CreateMovieRequest;
use App\Shared\Movie\Application\CreateMovie\CreateMovieService;
use App\Shared\Movie\Infra\MovieRepositoryImpl;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CreateMovieController extends AbstractController implements JwtAuthenticatedInterface
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
     * @Route("/api/movies", methods={"POST"})
     */
    public function createOrder(Request $request): Response
    {
        $userCredentials = $this->loggedUserService->getCredentials($request);
        $data = json_decode($request->getContent(), true);
        $movieId = Uuid::uuid4()->toString();

        $createMovieRequest = new CreateMovieRequest();
        $createMovieRequest->setUuid($movieId);
        $createMovieRequest->setUserProfile($userCredentials->getProfile());
        $form = $this->createForm(CreateMovieType::class, $createMovieRequest);
        $form->submit($data);

        $createMovieService = new CreateMovieService($this->movieRepository);
        $response = $createMovieService->execute($createMovieRequest);

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($response->getData(), 'json'),
            Response::HTTP_CREATED
        );
    }
}
