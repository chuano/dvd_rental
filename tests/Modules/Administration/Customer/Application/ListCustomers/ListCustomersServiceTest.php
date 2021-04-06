<?php

declare(strict_types=1);

namespace App\Tests\Modules\Administration\Customer\Application\ListCustomers;

use App\Modules\Administration\Customer\Application\ListCustomers\ListCustomersRequest;
use App\Modules\Administration\Customer\Application\ListCustomers\ListCustomersService;
use App\Modules\Administration\Customer\Domain\Customer;
use App\Modules\Administration\Customer\Domain\CustomerRepositoryInterface;
use App\Shared\Domain\CompleteName;
use App\Shared\Domain\Credentials;
use App\Shared\Domain\Exception\ForbbidenException;
use App\Shared\Domain\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListCustomersServiceTest extends WebTestCase
{
    /** @test */
    public function should_list_cutomers_given_admin_user()
    {
        $page = 1;
        $limit = 10;
        $total = 100;
        $repository = $this->createMock(CustomerRepositoryInterface::class);
        $repository->method('getAll')->willReturn($this->getCustomers($limit));
        $repository->method('count')->willReturn($total);

        $request = new ListCustomersRequest($page, $limit, Credentials::ADMIN_PROFILE);
        $service = new ListCustomersService($repository);
        $response = $service->execute($request);

        $this->assertEquals($page, $response->getData()['page']);
        $this->assertEquals($limit, $response->getData()['limit']);
        $this->assertEquals($total, $response->getData()['total']);
        $this->assertCount($limit, $response->getData()['data']);
    }

    /** @test */
    public function should_throw_forbbiden_exception_given_non_admin_user()
    {
        $page = 1;
        $limit = 10;
        $repository = $this->createMock(CustomerRepositoryInterface::class);

        $request = new ListCustomersRequest($page, $limit, Credentials::USER_PROFILE);
        $service = new ListCustomersService($repository);

        $this->expectException(ForbbidenException::class);
        $service->execute($request);
    }

    private function getCustomers(int $limit): array
    {
        $customers = [];
        while (count($customers) < $limit) {
            $customers[] = new Customer(
                Uuid::create(\Ramsey\Uuid\Uuid::uuid4()->toString()),
                CompleteName::create('test', 'test', 'test')
            );
        }
        return $customers;
    }
}
