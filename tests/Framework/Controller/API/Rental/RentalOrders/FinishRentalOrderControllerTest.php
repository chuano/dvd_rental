<?php

declare(strict_types=1);

namespace App\Tests\Framework\Controller\API\Rental\RentalOrders;

use App\Modules\Rental\RentalOrder\Domain\RentalStatus;
use App\Shared\Movie\Domain\Movie;
use App\Tests\Framework\Controller\API\TokenHelperTrait;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FinishRentalOrderControllerTest extends WebTestCase
{
    use FixturesTrait;
    use TokenHelperTrait;

    private KernelBrowser $client;
    private string $token;

    public function setUp(): void
    {
        $this->client = self::createClient();
        $this->loadFixtures(['App\Framework\DataFixtures\AppFixtures']);
        $this->token = $this->getToken();
    }

    /** @test */
    public function should_finish_order()
    {
        $orderId = $this->createOrder();
        $this->client->request('PUT', '/api/rental/rental_orders/' . $orderId . '?token=' . $this->token);
        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(RentalStatus::DONE, $responseContent['status']);
    }

    private function createOrder(): string
    {
        $movie = $this->getMovie();
        $postData = [
            'movieId' => $movie->getId()->getValue(),
            'from' => (new DateTimeImmutable())->format(DATE_ISO8601),
            'to' => (new DateTimeImmutable())->modify('+1 day')->format(DATE_ISO8601),
        ];
        $this->client->request(
            'POST',
            '/api/rental/rental_orders?token=' . $this->token,
            [],
            [],
            [],
            json_encode($postData)
        );
        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        return $responseContent['id'];
    }

    private function getMovie(): Movie
    {
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);
        return $entityManager->getRepository(Movie::class)->findOneBy(['metadata.title' => 'Kill Bill']);
    }
}
