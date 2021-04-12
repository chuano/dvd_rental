<?php

declare(strict_types=1);

namespace App\Tests\Modules\Rental\User\Application\Registration;

use App\Modules\Rental\User\Application\Registration\DuplicatedEmailException;
use App\Modules\Rental\User\Application\Registration\RegistrationRequest;
use App\Modules\Rental\User\Application\Registration\RegistrationService;
use App\Modules\Rental\User\Domain\UserRepositoryInterface;
use App\Tests\Modules\Rental\User\UserTestsTrait;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationServiceTest extends WebTestCase
{
    use UserTestsTrait;

    /** @test */
    public function should_register_user_given_correct_data()
    {
        $respository = $this->createMock(UserRepositoryInterface::class);
        $respository->method('getByEmail')->willReturn(null);

        $request = $this->getCorrectRequest();
        $service = new RegistrationService($respository);
        $response = $service->execute($request);

        $this->assertEquals($request->getEmail(), $response->getData()['email']);
    }

    /** @test */
    public function should_throw_duplcated_email_exception_given_used_email()
    {
        $respository = $this->createMock(UserRepositoryInterface::class);
        $respository->method('getByEmail')->willReturn($this->getUser());

        $request = $this->getCorrectRequest();
        $service = new RegistrationService($respository);

        $this->expectException(DuplicatedEmailException::class);
        $service->execute($request);
    }

    private function getCorrectRequest(): RegistrationRequest
    {
        $request = new RegistrationRequest();
        $request->setPassword('12345678');
        $request->setEmail('test@domain.com');
        $request->setUuid(Uuid::uuid4()->toString());
        $request->setAddress('address');
        $request->setCity('city');
        $request->setNumber('1');
        $request->setZipCode('87873');
        $request->setState('state');
        $request->setFirstName('test');
        $request->setFirstSurname('test');
        $request->setSecondSurname('test');

        return $request;
    }
}
