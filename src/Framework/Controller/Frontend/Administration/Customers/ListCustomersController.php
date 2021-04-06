<?php

declare(strict_types=1);

namespace App\Framework\Controller\Frontend\Administration\Customers;

use App\Framework\Security\JwtFrontentdAdminAuthenticatedInterface;
use App\Framework\Security\LoggedUserService;
use App\Modules\Administration\Customer\Application\ListCustomers\ListCustomersRequest;
use App\Modules\Administration\Customer\Application\ListCustomers\ListCustomersService;
use App\Modules\Administration\Customer\Infra\CustomerRepositoryInterfaceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListCustomersController extends AbstractController implements JwtFrontentdAdminAuthenticatedInterface
{
    private CustomerRepositoryInterfaceImpl $customerRepository;
    private LoggedUserService $loggedUserService;

    public function __construct(CustomerRepositoryInterfaceImpl $customerRepository, LoggedUserService $loggedUserService)
    {
        $this->customerRepository = $customerRepository;
        $this->loggedUserService = $loggedUserService;
    }

    /**
     * @Route("/admin/customers", name="customer_list")
     */
    public function listCustomers(Request $request): Response
    {
        $userCredentials = $this->loggedUserService->getCredentials($request);
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $listCustomersRequest = new ListCustomersRequest($page, $limit, $userCredentials->getProfile());
        $listCustomersService = new ListCustomersService($this->customerRepository);
        $customersResponse = $listCustomersService->execute($listCustomersRequest);

        return $this->render(
            'frontend/administration/list_customers.html.twig',
            [
                'customers' => $customersResponse->getData()['data'],
                'logged' => true,
            ]
        );
    }
}
