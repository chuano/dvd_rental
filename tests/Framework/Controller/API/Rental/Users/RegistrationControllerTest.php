<?php

declare(strict_types=1);

namespace App\Tests\Framework\Controller\API\Rental\Users;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    use FixturesTrait;

    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
        $this->loadFixtures(['App\Framework\DataFixtures\AppFixtures']);
    }

    /** @test */
    public function should_register_user()
    {
        $postData = [
            'firstName' => 'TestName',
            'firstSurname' => 'TestFirstSurname',
            'secondSurname' => 'TestSecondSurname',
            'address' => 'TestAddress',
            'number' => '1',
            'city' => 'TestCity',
            'zipCode' => 'TestZipCode',
            'state' => 'TestState',
            'email' => 'testemail@testemaildomain.com',
            'password' => '12345678',
        ];

        $this->client->request('POST', '/api/rental/users', [], [], [], json_encode($postData));
        $response = $this->client->getResponse();

        $this->assertEquals(201, $response->getStatusCode());
    }
}
