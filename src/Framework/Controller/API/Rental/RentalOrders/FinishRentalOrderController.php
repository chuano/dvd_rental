<?php

declare(strict_types=1);

namespace App\Framework\Controller\API\Rental\RentalOrders;

use App\Framework\Security\JwtAuthenticatedInterface;
use App\Framework\Security\LoggedUserService;
use App\Modules\Rental\RentalOrder\Application\FinishRentalOrder\FinishRentalOrderRequest;
use App\Modules\Rental\RentalOrder\Application\FinishRentalOrder\FinishRentalOrderService;
use App\Modules\Rental\RentalOrder\Infra\RentalOrderRepositoryImpl;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class FinishRentalOrderController implements JwtAuthenticatedInterface
{
    private RentalOrderRepositoryImpl $rentalOrderRepository;
    private SerializerInterface $serializer;
    private LoggedUserService $loggedUserService;

    public function __construct(
        RentalOrderRepositoryImpl $rentalOrderRepository,
        SerializerInterface $serializer,
        LoggedUserService $loggedUserService
    ) {
        $this->rentalOrderRepository = $rentalOrderRepository;
        $this->serializer = $serializer;
        $this->loggedUserService = $loggedUserService;
    }

    /**
     * @Route("/api/rental/rental_orders/{orderId}", methods={"PUT","PATCH"} )
     */
    public function finishRentalOrder(Request $request, string $orderId): Response
    {
        $userCredentials = $this->loggedUserService->getCredentials($request);

        $finishRentalOrderRequest = new FinishRentalOrderRequest($orderId, $userCredentials->getId()->getValue());
        $finishRentalOrderService = new FinishRentalOrderService($this->rentalOrderRepository);
        $response = $finishRentalOrderService->execute($finishRentalOrderRequest);

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($response->getData(), 'json'),
            Response::HTTP_OK
        );
    }
}
