<?php

declare(strict_types=1);

namespace App\Tests\Modules\Rental\User\Application\Login;

use App\Modules\Rental\User\Application\Login\LoginRequest;
use App\Modules\Rental\User\Application\Login\LoginService;
use App\Modules\Rental\User\Domain\UserRepositoryInterface;
use App\Shared\Infra\JwtServiceImpl;
use App\Tests\Modules\Rental\User\UserTestsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginServiceTest extends WebTestCase
{
    use UserTestsTrait;

    /** @test */
    public function should_create_token_given_correct_data()
    {
        $user = $this->getUser();
        $repository = $this->createMock(UserRepositoryInterface::class);
        $repository->method('getByEmail')->willReturn($user);
        $jwtService = new JwtServiceImpl('');

        $request = new LoginRequest();
        $request->setEmail('test@domain.com');
        $request->setPassword('12345678');
        $service = new LoginService($repository, $jwtService);
        $response = $service->execute($request);

        $this->assertArrayHasKey('token', $response->getData());
    }
}
