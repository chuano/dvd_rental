<?php

declare(strict_types=1);

namespace App\Tests\Modules\Administration\Sale\Application\ListSales;

use App\Modules\Administration\Sale\Application\ListSales\ListSalesRequest;
use App\Modules\Administration\Sale\Application\ListSales\ListSalesService;
use App\Modules\Administration\Sale\Domain\MovieTitle;
use App\Modules\Administration\Sale\Domain\Sale;
use App\Modules\Administration\Sale\Domain\SaleRepositoryInterface;
use App\Shared\Domain\CompleteName;
use App\Shared\Domain\Credentials;
use App\Shared\Domain\Exception\ForbiddenException;
use App\Shared\Domain\Uuid;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListSalesServiceTest extends WebTestCase
{
    /** @test */
    public function should_list_sales_given_admin_user()
    {
        $page = 1;
        $limit = 10;
        $total = 100;
        $repository = $this->createMock(SaleRepositoryInterface::class);
        $repository->method('getAll')->willReturn($this->getSales($limit));
        $repository->method('count')->willReturn($total);

        $request = new ListSalesRequest($page, $limit, Credentials::ADMIN_PROFILE);
        $service = new ListSalesService($repository);
        $response = $service->execute($request);

        $this->assertEquals($page, $response->getData()['page']);
        $this->assertEquals($limit, $response->getData()['limit']);
        $this->assertEquals($total, $response->getData()['total']);
        $this->assertCount($limit, $response->getData()['data']);
    }

    /** @test */
    public function should_throw_forbidden_exception_given_non_admin_user()
    {
        $page = 1;
        $limit = 10;
        $repository = $this->createMock(SaleRepositoryInterface::class);

        $request = new ListSalesRequest($page, $limit, Credentials::USER_PROFILE);
        $service = new ListSalesService($repository);

        $this->expectException(ForbiddenException::class);
        $service->execute($request);
    }

    private function getSales(int $limit): array
    {
        $customers = [];
        while (count($customers) < $limit) {
            $customers[] = new Sale(
                Uuid::create(\Ramsey\Uuid\Uuid::uuid4()->toString()),
                new MovieTitle('title'),
                Uuid::create(\Ramsey\Uuid\Uuid::uuid4()->toString()),
                CompleteName::create('test', 'test', 'test'),
                Uuid::create(\Ramsey\Uuid\Uuid::uuid4()->toString()),
                new DateTimeImmutable()
            );
        }
        return $customers;
    }
}
