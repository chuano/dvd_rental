<?php

declare(strict_types=1);

namespace App\Framework\Controller\API\Rental\RentalOrders;

use App\Framework\Security\JwtAuthenticatedInterface;
use App\Framework\Security\LoggedUserService;
use App\Modules\Rental\RentalOrder\Application\UserRentalOrders\UserRentalOrdersRequest;
use App\Modules\Rental\RentalOrder\Application\UserRentalOrders\UserRentalOrdersService;
use App\Modules\Rental\RentalOrder\Infra\RentalOrderRepositoryImpl;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserRentalOrdersController implements JwtAuthenticatedInterface
{
    private RentalOrderRepositoryImpl $rentalOrderRepository;
    private LoggedUserService $loggedUserService;
    private SerializerInterface $serializer;

    public function __construct(
        RentalOrderRepositoryImpl $rentalOrderRepository,
        LoggedUserService $loggedUserService,
        SerializerInterface $serializer
    ) {
        $this->rentalOrderRepository = $rentalOrderRepository;
        $this->loggedUserService = $loggedUserService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/rental/rental_orders", methods={"GET"})
     */
    public function getRentalOrders(Request $request): Response
    {
        $userCredentials = $this->loggedUserService->getCredentials($request);

        $userRentalOrdersRequest = new UserRentalOrdersRequest($userCredentials->getId()->getValue());
        $userRentalOrdersService = new UserRentalOrdersService($this->rentalOrderRepository);
        $response = $userRentalOrdersService->execute($userRentalOrdersRequest);

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($response->getData(), 'json'),
            Response::HTTP_OK
        );
    }
}
