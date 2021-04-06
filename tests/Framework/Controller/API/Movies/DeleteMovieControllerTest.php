<?php

declare(strict_types=1);

namespace App\Tests\Framework\Controller\API\Movies;

use App\Tests\Framework\Controller\API\TokenHelperTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteMovieControllerTest extends WebTestCase
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
    public function should_create_movie()
    {
        $movieId = $this->getMovieId();
        $url = '/api/movies/' . $movieId . '?token=' . $this->token;
        $this->client->request('DELETE', $url);
        $response = $this->client->getResponse();
        $movieIdAfter = $this->getMovieId();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertNotEquals($movieId, $movieIdAfter);
    }

    private function getMovieId(): string
    {
        $this->client->request('GET', '/api/movies?token=' . $this->token);
        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        return $responseContent['data'][0]['id'];
    }
}
