<?php

declare(strict_types=1);

namespace App\Tests\Framework\Controller\API\Administration\AdminUsers;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminLoginControllerTest extends WebTestCase
{
    use FixturesTrait;

    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
        $this->loadFixtures(['App\Framework\DataFixtures\AppFixtures']);
    }

    /** @test */
    public function should_return_token()
    {
        $postData = [
            'email' => 'testadmin@domain.com',
            'password' => '12345678',
        ];
        $this->client->request('POST', '/api/administration/users/tokens', [], [], [], json_encode($postData));
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertObjectHasAttribute('token', json_decode($response->getContent()));
    }
}
