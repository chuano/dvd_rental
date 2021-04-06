<?php

declare(strict_types=1);

namespace App\Framework\Controller\Frontend\Administration\Sales;

use App\Framework\Security\JwtFrontentdAdminAuthenticatedInterface;
use App\Framework\Security\LoggedUserService;
use App\Modules\Administration\Sale\Application\ListSales\ListSalesRequest;
use App\Modules\Administration\Sale\Application\ListSales\ListSalesService;
use App\Modules\Administration\Sale\Infra\SaleRepositoryImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListSalesController extends AbstractController implements JwtFrontentdAdminAuthenticatedInterface
{
    private SaleRepositoryImpl $saleRepository;
    private LoggedUserService $loggedUserService;

    public function __construct(SaleRepositoryImpl $saleRepository, LoggedUserService $loggedUserService)
    {
        $this->saleRepository = $saleRepository;
        $this->loggedUserService = $loggedUserService;
    }

    /**
     * @Route("/admin/sales", name="sales_list")
     */
    public function listCustomers(Request $request): Response
    {
        $userCredentials = $this->loggedUserService->getCredentials($request);
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $listSalesRequest = new ListSalesRequest($page, $limit, $userCredentials->getProfile());
        $listSalesService = new ListSalesService($this->saleRepository);
        $response = $listSalesService->execute($listSalesRequest);

        return $this->render(
            'frontend/administration/list_sales.html.twig',
            [
                'sales' => $response->getData()['data'],
                'logged' => true,
            ]
        );
    }
}
