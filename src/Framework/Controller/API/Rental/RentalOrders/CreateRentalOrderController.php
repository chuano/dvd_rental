<?php

declare(strict_types=1);

namespace App\Framework\Controller\API\Rental\RentalOrders;

use App\Framework\Form\CreateRentalOrderType;
use App\Framework\Security\JwtAuthenticatedInterface;
use App\Framework\Security\LoggedUserService;
use App\Modules\Rental\RentalOrder\Application\CreateRentalOrder\CreateRentalOrderRequest;
use App\Modules\Rental\RentalOrder\Application\CreateRentalOrder\CreateRentalOrderService;
use App\Modules\Rental\RentalOrder\Infra\RentalOrderRepositoryImpl;
use App\Modules\Rental\User\Infra\UserRepositoryImpl;
use App\Shared\Movie\Infra\MovieRepositoryImpl;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CreateRentalOrderController extends AbstractController implements JwtAuthenticatedInterface
{
    private UserRepositoryImpl $userRepository;
    private MovieRepositoryImpl $movieRepository;
    private RentalOrderRepositoryImpl $rentalOrderRepository;
    private SerializerInterface $serializer;
    private LoggedUserService $loggedUserService;

    public function __construct(
        UserRepositoryImpl $userRepository,
        MovieRepositoryImpl $movieRepository,
        RentalOrderRepositoryImpl $rentalOrderRepository,
        SerializerInterface $serializer,
        LoggedUserService $loggedUserService
    ) {
        $this->userRepository = $userRepository;
        $this->movieRepository = $movieRepository;
        $this->rentalOrderRepository = $rentalOrderRepository;
        $this->serializer = $serializer;
        $this->loggedUserService = $loggedUserService;
    }

    /**
     * @Route("/api/rental/rental_orders", methods={"POST"})
     */
    public function createOrder(Request $request): Response
    {
        $userCredentials = $this->loggedUserService->getCredentials($request);
        $data = json_decode($request->getContent(), true);
        $orderId = Uuid::uuid4()->toString();
        $userId = $userCredentials->getId()->getValue();

        $createOrderRequest = new CreateRentalOrderRequest();
        $createOrderRequest->setOrderId($orderId);
        $createOrderRequest->setUserId($userId);
        $form = $this->createForm(CreateRentalOrderType::class, $createOrderRequest);
        $form->submit($data);

        $createOrderService = new CreateRentalOrderService(
            $this->userRepository,
            $this->movieRepository,
            $this->rentalOrderRepository
        );
        $response = $createOrderService->execute($createOrderRequest);

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($response->getData(), 'json'),
            Response::HTTP_CREATED
        );
    }
}
