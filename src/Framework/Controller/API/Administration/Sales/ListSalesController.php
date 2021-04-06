<?php

declare(strict_types=1);

namespace App\Framework\Controller\API\Administration\Sales;

use App\Framework\Security\LoggedUserService;
use App\Modules\Administration\Sale\Application\ListSales\ListSalesRequest;
use App\Modules\Administration\Sale\Application\ListSales\ListSalesService;
use App\Modules\Administration\Sale\Infra\SaleRepositoryImpl;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ListSalesController
{
    private SaleRepositoryImpl $saleRepository;
    private SerializerInterface $serializer;
    private LoggedUserService $loggedUserService;

    public function __construct(
        SaleRepositoryImpl $saleRepository,
        SerializerInterface $serializer,
        LoggedUserService $loggedUserService
    ) {
        $this->saleRepository = $saleRepository;
        $this->serializer = $serializer;
        $this->loggedUserService = $loggedUserService;
    }

    /**
     * @Route("/api/administration/sales", methods={"GET"})
     */
    public function listSales(Request $request): Response
    {
        $userCredentials = $this->loggedUserService->getCredentials($request);
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $listSalesRequest = new ListSalesRequest($page, $limit, $userCredentials->getProfile());
        $listSalesService = new ListSalesService($this->saleRepository);
        $response = $listSalesService->execute($listSalesRequest);

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($response->getData(), 'json'),
            Response::HTTP_OK
        );
    }
}
