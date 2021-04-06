<?php

declare(strict_types=1);

namespace App\Framework\Controller\Frontend\Administration\Movies;

use App\Framework\Security\JwtFrontentdAdminAuthenticatedInterface;
use App\Framework\Security\LoggedUserService;
use App\Modules\Rental\RentalOrder\Infra\RentalOrderRepositoryImpl;
use App\Shared\Movie\Application\ListMovies\ListMoviesRequest;
use App\Shared\Movie\Application\ListMovies\ListMoviesService;
use App\Shared\Movie\Infra\MovieRepositoryImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListMoviesController extends AbstractController implements JwtFrontentdAdminAuthenticatedInterface
{
    private MovieRepositoryImpl $movieRepository;
    private LoggedUserService $loggedUserService;
    private RentalOrderRepositoryImpl $rentalOrderRepository;

    public function __construct(
        MovieRepositoryImpl $movieRepository,
        RentalOrderRepositoryImpl $rentalOrderRepository,
        LoggedUserService $loggedUserService
    ) {
        $this->movieRepository = $movieRepository;
        $this->rentalOrderRepository = $rentalOrderRepository;
        $this->loggedUserService = $loggedUserService;
    }

    /**
     * @Route("/admin/movies", name="admin_movie_list")
     */
    public function listMovies(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $listMoviesRequest = new ListMoviesRequest($page, $limit);
        $listMoviesService = new ListMoviesService($this->movieRepository);
        $moviesResponse = $listMoviesService->execute($listMoviesRequest);

        return $this->render(
            'frontend/administration/list_movies.html.twig',
            [
                'movies' => $moviesResponse->getData()['data'],
                'logged' => true,
            ]
        );
    }
}
