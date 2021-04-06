<?php

declare(strict_types=1);

namespace App\Framework\Controller\Frontend\Administration\Movies;

use App\Framework\Form\CreateMovieType;
use App\Framework\Security\JwtFrontentdAdminAuthenticatedInterface;
use App\Framework\Security\LoggedUserService;
use App\Shared\Movie\Application\CreateMovie\CreateMovieRequest;
use App\Shared\Movie\Application\CreateMovie\CreateMovieService;
use App\Shared\Movie\Infra\MovieRepositoryImpl;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class CreateMovieController extends AbstractController implements JwtFrontentdAdminAuthenticatedInterface
{
    private MovieRepositoryImpl $movieRepository;
    private LoggedUserService $loggedUserService;

    public function __construct(MovieRepositoryImpl $movieRepository, LoggedUserService $loggedUserService)
    {
        $this->movieRepository = $movieRepository;
        $this->loggedUserService = $loggedUserService;
    }

    /**
     * @Route("/movies/new", name="new_movie")
     */
    public function create(Request $request): Response
    {
        $userCredentials = $this->loggedUserService->getCredentials($request);
        $movieId = Uuid::uuid4()->toString();

        $createMovieRequest = new CreateMovieRequest();
        $createMovieRequest->setUuid($movieId);
        $createMovieRequest->setUserProfile($userCredentials->getProfile());
        $form = $this->createForm(CreateMovieType::class, $createMovieRequest);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $createMovieService = new CreateMovieService($this->movieRepository);
                $createMovieService->execute($createMovieRequest);
                $this->addFlash('success', 'Movie created successfully');
                return new RedirectResponse('/admin/movies');
            } catch (Throwable) {
                $this->addFlash('error', 'Movie not created');
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
}
