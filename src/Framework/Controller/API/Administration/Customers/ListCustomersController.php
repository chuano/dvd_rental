<?php

declare(strict_types=1);

namespace App\Framework\Controller\API\Administration\Customers;

use App\Framework\Security\JwtAuthenticatedInterface;
use App\Framework\Security\LoggedUserService;
use App\Modules\Administration\Customer\Application\ListCustomers\ListCustomersRequest;
use App\Modules\Administration\Customer\Application\ListCustomers\ListCustomersService;
use App\Modules\Administration\Customer\Infra\CustomerRepositoryInterfaceImpl;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ListCustomersController implements JwtAuthenticatedInterface
{
    private CustomerRepositoryInterfaceImpl $customerRepository;
    private SerializerInterface $serializer;
    private LoggedUserService $loggedUserService;

    public function __construct(
        CustomerRepositoryInterfaceImpl $customerRepository,
        SerializerInterface $serializer,
        LoggedUserService $loggedUserService
    ) {
        $this->customerRepository = $customerRepository;
        $this->serializer = $serializer;
        $this->loggedUserService = $loggedUserService;
    }

    /**
     * @Route("/api/administration/customers", methods={"GET"})
     */
    public function listCustomers(Request $request): Response
    {
        $userCredentials = $this->loggedUserService->getCredentials($request);
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $listCustomersRequest = new ListCustomersRequest($page, $limit, $userCredentials->getProfile());
        $listCustomersService = new ListCustomersService($this->customerRepository);
        $response = $listCustomersService->execute($listCustomersRequest);

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($response->getData(), 'json'),
            Response::HTTP_OK
        );
    }
}
