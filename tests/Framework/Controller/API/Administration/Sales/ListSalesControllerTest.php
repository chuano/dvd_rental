<?php

declare(strict_types=1);

namespace App\Tests\Framework\Controller\API\Administration\Sales;

use App\Tests\Framework\Controller\API\TokenHelperTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListSalesControllerTest extends WebTestCase
{
    use FixturesTrait;
    use TokenHelperTrait;

    private KernelBrowser $client;
    private string $token;

    public function setUp(): void
    {
        $this->client = self::createClient();
        $this->loadFixtures(['App\Framework\DataFixtures\AppFixtures']);
        $this->token = $this->getAdminToken();
    }

    /** @test */
    public function should_return_sales_list()
    {
        $this->client->request(
            'GET',
            '/api/administration/sales',
            ['page' => 1, 'limit' => 1, 'token' => $this->token]
        );
        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(1, $responseContent['data']);
    }
}
