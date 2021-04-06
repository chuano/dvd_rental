<?php

declare(strict_types=1);

namespace App\Framework\Controller\Frontend\Administration\Movies;

use App\Framework\Security\JwtFrontentdAdminAuthenticatedInterface;
use App\Framework\Security\LoggedUserService;
use App\Shared\Domain\Credentials;
use App\Shared\Domain\Exception\ForbbidenException;
use App\Shared\Movie\Application\DeleteMovie\DeleteMovieRequest;
use App\Shared\Movie\Application\DeleteMovie\DeleteMovieService;
use App\Shared\Movie\Infra\MovieRepositoryImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class DeleteMovieController extends AbstractController implements JwtFrontentdAdminAuthenticatedInterface
{
    private MovieRepositoryImpl $movieRepository;
    private LoggedUserService $loggedUserService;

    public function __construct(MovieRepositoryImpl $movieRepository, LoggedUserService $loggedUserService)
    {
        $this->movieRepository = $movieRepository;
        $this->loggedUserService = $loggedUserService;
    }

    /**
     * @Route("/administration/movies/delete/{movieId}", name="delete_movie")
     */
    public function createOrder(Request $request, string $movieId): Response
    {
        try {
            $userCredentials = $this->loggedUserService->getCredentials($request);
            $deleteMovieRequest = new DeleteMovieRequest();
            $deleteMovieRequest->setUserProfile($userCredentials->getProfile());
            $deleteMovieRequest->setId($movieId);

            $deleteMovieService = new DeleteMovieService($this->movieRepository);
            $deleteMovieService->execute($deleteMovieRequest);
            $this->addFlash('success', 'Movie deleted successfully');
        } catch (Throwable) {
            $this->addFlash('error', 'Movie not deleted');
        }

        return new RedirectResponse('/admin/movies');
    }
}
