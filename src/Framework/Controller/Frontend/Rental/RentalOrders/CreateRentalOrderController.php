<?php

declare(strict_types=1);

namespace App\Framework\Controller\Frontend\Rental\RentalOrders;

use App\Framework\Form\CreateRentalOrderType;
use App\Framework\Security\JwtFrontentdAuthenticatedInterface;
use App\Framework\Security\LoggedUserService;
use App\Modules\Rental\RentalOrder\Application\CreateRentalOrder\CreateRentalOrderRequest;
use App\Modules\Rental\RentalOrder\Application\CreateRentalOrder\CreateRentalOrderService;
use App\Modules\Rental\RentalOrder\Infra\RentalOrderRepositoryImpl;
use App\Modules\Rental\User\Infra\UserRepositoryImpl;
use App\Shared\Movie\Infra\MovieRepositoryImpl;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class CreateRentalOrderController extends AbstractController implements JwtFrontentdAuthenticatedInterface
{
    private UserRepositoryImpl $userRepository;
    private MovieRepositoryImpl $movieRepository;
    private RentalOrderRepositoryImpl $rentalOrderRepository;
    private LoggedUserService $loggedUserService;

    public function __construct(
        UserRepositoryImpl $userRepository,
        MovieRepositoryImpl $movieRepository,
        RentalOrderRepositoryImpl $rentalOrderRepository,
        LoggedUserService $loggedUserService
    ) {
        $this->userRepository = $userRepository;
        $this->movieRepository = $movieRepository;
        $this->rentalOrderRepository = $rentalOrderRepository;
        $this->loggedUserService = $loggedUserService;
    }

    /**
     * @Route("/rent/{movieId}", name="rent")
     */
    public function create(Request $request, string $movieId): Response
    {
        $userCredentials = $this->loggedUserService->getCredentials($request);
        $orderId = Uuid::uuid4()->toString();
        $userId = $userCredentials->getId()->getValue();

        $createOrderRequest = new CreateRentalOrderRequest();
        $createOrderRequest->setOrderId($orderId);
        $createOrderRequest->setUserId($userId);
        $createOrderRequest->setMovieId($movieId);
        $createOrderRequest->setFrom(new DateTimeImmutable());
        $createOrderRequest->setTo((new DateTimeImmutable())->modify('+1 day'));
        $form = $this->createForm(CreateRentalOrderType::class, $createOrderRequest);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $createOrderService = new CreateRentalOrderService(
                    $this->userRepository,
                    $this->movieRepository,
                    $this->rentalOrderRepository
                );
                $createOrderService->execute($createOrderRequest);

                $this->addFlash('success', 'Movie rented successfully');
                return new RedirectResponse('/');
            } catch (Throwable $e) {
                $this->addFlash('error', 'Rent error: ' . $e->getMessage());
            }
        }

        return $this->render(
            'frontend/rental/rent.html.twig',
            [
                'rentForm' => $form->createView(),
                'logged' => true,
            ]
        );
    }
}
