<?php

declare(strict_types=1);

namespace App\Framework\Controller\Frontend\Rental\RentalOrders;

use App\Framework\Security\JwtFrontentdAuthenticatedInterface;
use App\Framework\Security\LoggedUserService;
use App\Modules\Rental\RentalOrder\Application\FinishRentalOrder\FinishRentalOrderRequest;
use App\Modules\Rental\RentalOrder\Application\FinishRentalOrder\FinishRentalOrderService;
use App\Modules\Rental\RentalOrder\Infra\RentalOrderRepositoryImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class FinishRentalOrderController extends AbstractController implements JwtFrontentdAuthenticatedInterface
{
    private RentalOrderRepositoryImpl $rentalOrderRepository;
    private LoggedUserService $loggedUserService;

    public function __construct(RentalOrderRepositoryImpl $rentalOrderRepository, LoggedUserService $loggedUserService)
    {
        $this->rentalOrderRepository = $rentalOrderRepository;
        $this->loggedUserService = $loggedUserService;
    }

    /**
     * @Route("/return/{orderId}", name="return")
     */
    public function returnMovie(Request $request, string $orderId): Response
    {
        try {
            $userCredentials = $this->loggedUserService->getCredentials($request);

            $finishRentalOrderRequest = new FinishRentalOrderRequest($orderId, $userCredentials->getId()->getValue());
            $finishRentalOrderService = new FinishRentalOrderService($this->rentalOrderRepository);
            $finishRentalOrderService->execute($finishRentalOrderRequest);
            $this->addFlash('success', 'Movie returned successfully');
        } catch (Throwable) {
            $this->addFlash('error', 'Return error');
        }

        return new RedirectResponse('/');
    }
}
