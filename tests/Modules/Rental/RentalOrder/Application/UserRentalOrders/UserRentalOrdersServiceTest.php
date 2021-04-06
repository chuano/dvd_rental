<?php

declare(strict_types=1);

namespace App\Tests\Modules\Rental\RentalOrder\Application\UserRentalOrders;

use App\Modules\Rental\RentalOrder\Application\UserRentalOrders\UserRentalOrdersRequest;
use App\Modules\Rental\RentalOrder\Application\UserRentalOrders\UserRentalOrdersService;
use App\Modules\Rental\RentalOrder\Domain\RentalOrderRepositoryInterface;
use App\Modules\Rental\RentalOrder\Domain\RentalStatus;
use App\Modules\Rental\User\Application\Login\InvalidCredentialsException;
use App\Modules\Rental\User\Application\Login\LoginRequest;
use App\Modules\Rental\User\Application\Login\LoginService;
use App\Modules\Rental\User\Domain\UserRepositoryInterface;
use App\Shared\Infra\JwtServiceImpl;
use App\Tests\Modules\Rental\RentalOrder\RentalOrderTestsTrait;
use App\Tests\Modules\Rental\User\UserTestsTrait;
use App\Tests\Shared\Movie\Application\MovieTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRentalOrdersServiceTest extends WebTestCase
{
    use RentalOrderTestsTrait;
    use UserTestsTrait;
    use MovieTestTrait;

    /** @test */
    public function should_get_list_of_user_orders()
    {
        $user = $this->getUser();
        $movie = $this->getMovie(1);
        $status = new RentalStatus(RentalStatus::DONE);
        $order = $this->getOrder($user, $movie, $status);
        $repository = $this->createMock(RentalOrderRepositoryInterface::class);
        $repository->method('getByUserId')->willReturn([$order]);

        $request = new UserRentalOrdersRequest($user->getId()->getValue());
        $service = new UserRentalOrdersService($repository);
        $response = $service->execute($request);

        $this->assertCount(1, $response->getData()['data']);
    }

    /** @test */
    public function should_throw_invalid_credentials_given_inexistent_user()
    {
        $repository = $this->createMock(UserRepositoryInterface::class);
        $repository->method('getByEmail')->willReturn(null);
        $jwtService = new JwtServiceImpl('');

        $request = new LoginRequest();
        $request->setEmail('inexistent@domain.com');
        $request->setPassword('12345678');
        $service = new LoginService($repository, $jwtService);

        $this->expectException(InvalidCredentialsException::class);
        $service->execute($request);
    }

    /** @test */
    public function should_throw_invalid_credentials_given_incorrect_password()
    {
        $user = $this->getUser();
        $repository = $this->createMock(UserRepositoryInterface::class);
        $repository->method('getByEmail')->willReturn($user);
        $jwtService = new JwtServiceImpl('');

        $request = new LoginRequest();
        $request->setEmail('test@domain.com');
        $request->setPassword('');
        $service = new LoginService($repository, $jwtService);

        $this->expectException(InvalidCredentialsException::class);
        $service->execute($request);
    }
}
