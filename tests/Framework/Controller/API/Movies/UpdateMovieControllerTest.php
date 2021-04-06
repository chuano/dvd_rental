<?php

declare(strict_types=1);

namespace App\Tests\Framework\Controller\API\Movies;

use App\Tests\Framework\Controller\API\TokenHelperTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateMovieControllerTest extends WebTestCase
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
    public function should_update_movie()
    {
        $movieId = $this->getMovieId();
        $postData = [
            'title' => 'Malditos Bastardos1',
            'year' => 2010,
            'synopsis' => 'Segunda Guerra Mundial (1939-1945). En la Francia ocupada por los alemanes...1',
            'stock' => 2
        ];
        $url = '/api/movies/' . $movieId . '?token=' . $this->token;
        $this->client->request('PUT', $url, [], [], [], json_encode($postData));
        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($postData['title'], $responseContent['title']);
        $this->assertEquals($postData['year'], $responseContent['year']);
        $this->assertEquals($postData['synopsis'], $responseContent['synopsis']);
        $this->assertEquals($postData['stock'], $responseContent['stock']);
    }

    private function getMovieId(): string
    {
        $postData = [
            'title' => 'Malditos Bastardos',
            'year' => 2009,
            'synopsis' => 'Segunda Guerra Mundial (1939-1945). En la Francia ocupada por los alemanes...',
            'stock' => 1
        ];
        $this->client->request('POST', '/api/movies?token=' . $this->token, [], [], [], json_encode($postData));
        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        return $responseContent['id'];
    }
}
