<?php

declare(strict_types=1);

namespace App\Tests\Framework\Controller\API\Rental\RentalOrders;

use App\Modules\Rental\User\Domain\User;
use App\Shared\Movie\Domain\Movie;
use App\Tests\Framework\Controller\API\TokenHelperTrait;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateRentalOrderControlleTest extends WebTestCase
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
    public function should_create_order()
    {

        $postData = [
            'movieId' => $this->getMovie()->getId()->getValue(),
            'from' => (new DateTimeImmutable())->format('Y-m-d'),
            'to' => (new DateTimeImmutable())->modify('+1 day')->format('Y-m-d'),
        ];
        $this->client->request('POST', '/api/rental/rental_orders?token=' . $this->token, [], [], [], json_encode($postData));
        $response = $this->client->getResponse();

        $this->assertEquals(201, $response->getStatusCode());
    }

    private function getUser(): User
    {
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);
        return $entityManager->getRepository(User::class)->findOneBy(['email.value' => 'test@domain.com']);
    }

    private function getMovie(): Movie
    {
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);
        return $entityManager->getRepository(Movie::class)->findOneBy(['metadata.title' => 'Kill Bill']);
    }
}
