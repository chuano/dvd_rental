<?php

declare(strict_types=1);

namespace App\Tests\Modules\Administration\AdminUser\Application\Login;

use App\Modules\Administration\AdminUser\Application\Login\AdminLoginRequest;
use App\Modules\Administration\AdminUser\Application\Login\AdminLoginService;
use App\Modules\Administration\AdminUser\Domain\AdminUser;
use App\Modules\Administration\AdminUser\Domain\AdminUserRepositoryInterface;
use App\Shared\Domain\EmailAddress;
use App\Shared\Domain\Exception\InvalidCredentialsException;
use App\Shared\Domain\Password;
use App\Shared\Domain\Uuid;
use App\Shared\Infra\JwtServiceImpl;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminLoginServiceTest extends WebTestCase
{
    /** @test */
    public function should_create_token_given_correct_data()
    {
        $user = $this->getUser();
        $repository = $this->createMock(AdminUserRepositoryInterface::class);
        $repository->method('getByEmail')->willReturn($user);
        $jwtService = new JwtServiceImpl('');

        $request = new AdminLoginRequest();
        $request->setEmail('test@domain.com');
        $request->setPassword('12345678');
        $service = new AdminLoginService($repository, $jwtService);
        $response = $service->execute($request);

        $this->assertArrayHasKey('token', $response->getData());
    }

    /** @test */
    public function should_throw_invalid_credentials_given_inexistent_user()
    {
        $repository = $this->createMock(AdminUserRepositoryInterface::class);
        $repository->method('getByEmail')->willReturn(null);
        $jwtService = new JwtServiceImpl('');

        $request = new AdminLoginRequest();
        $request->setEmail('inexistent@domain.com');
        $request->setPassword('12345678');
        $service = new AdminLoginService($repository, $jwtService);

        $this->expectException(InvalidCredentialsException::class);
        $service->execute($request);
    }

    /** @test */
    public function should_throw_invalid_credentials_given_incorrect_password()
    {
        $user = $this->getUser();
        $repository = $this->createMock(AdminUserRepositoryInterface::class);
        $repository->method('getByEmail')->willReturn($user);
        $jwtService = new JwtServiceImpl('');

        $request = new AdminLoginRequest();
        $request->setEmail('test@domain.com');
        $request->setPassword('');
        $service = new AdminLoginService($repository, $jwtService);

        $this->expectException(InvalidCredentialsException::class);
        $service->execute($request);
    }

    private function getUser(): AdminUser
    {
        return new AdminUser(
            Uuid::create(\Ramsey\Uuid\Uuid::uuid4()->toString()),
            EmailAddress::create('test@domain.com'),
            Password::create('12345678')
        );
    }
}
