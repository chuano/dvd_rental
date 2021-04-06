<?php

declare(strict_types=1);

namespace App\Framework\Controller\Frontend\Administration\Movies;

use App\Framework\Form\CreateMovieType;
use App\Framework\Form\UpdateMovieType;
use App\Framework\Security\JwtFrontentdAdminAuthenticatedInterface;
use App\Framework\Security\LoggedUserService;
use App\Shared\Domain\Credentials;
use App\Shared\Domain\Uuid;
use App\Shared\Movie\Application\CreateMovie\CreateMovieRequest;
use App\Shared\Movie\Application\CreateMovie\CreateMovieService;
use App\Shared\Movie\Application\UpdateMovie\UpdateMovieRequest;
use App\Shared\Movie\Application\UpdateMovie\UpdateMovieService;
use App\Shared\Movie\Infra\MovieRepositoryImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class UpdateMovieController extends AbstractController implements JwtFrontentdAdminAuthenticatedInterface
{
    private MovieRepositoryImpl $movieRepository;
    private LoggedUserService $loggedUserService;

    public function __construct(MovieRepositoryImpl $movieRepository, LoggedUserService $loggedUserService)
    {
        $this->movieRepository = $movieRepository;
        $this->loggedUserService = $loggedUserService;
    }

    /**
     * @Route("/movies/edit/{movieId}", name="edit_movie")
     */
    public function create(Request $request, string $movieId): Response
    {
        $userCredentials = $this->loggedUserService->getCredentials($request);
        $createMovieRequest = $this->getRequest($movieId, $userCredentials);
        $form = $this->createForm(UpdateMovieType::class, $createMovieRequest);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $updateMovieService = new UpdateMovieService($this->movieRepository);
                $updateMovieService->execute($createMovieRequest);
                $this->addFlash('success', 'Movie updated successfully');
                return new RedirectResponse('/admin/movies');
            } catch (Throwable $e) {
                $this->addFlash('error', 'Movie not updated');
            }
        }

        return $this->render(
            'frontend/administration/movie_form.html.twig',
            [
                'movieForm' => $form->createView(),
                'logged' => true,
            ]
        );
    }

    public function getRequest(string $movieId, Credentials $userCredentials): UpdateMovieRequest
    {
        $movie = $this->movieRepository->getById(Uuid::create($movieId));
        $updateMovieRequest = new UpdateMovieRequest();
        $updateMovieRequest->setId($movieId);
        $updateMovieRequest->setTitle($movie->getMetadata()->getTitle());
        $updateMovieRequest->setYear($movie->getMetadata()->getYear());
        $updateMovieRequest->setSynopsis($movie->getMetadata()->getSynopsis());
        $updateMovieRequest->setStock($movie->getStock());
        $updateMovieRequest->setUserProfile($userCredentials->getProfile());

        return $updateMovieRequest;
    }
}
