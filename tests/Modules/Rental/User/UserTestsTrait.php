<?php


namespace App\Tests\Modules\Rental\User;


use App\Modules\Rental\User\Domain\User;
use App\Modules\Rental\User\Domain\UserPostalAddress;
use App\Shared\Domain\CompleteName;
use App\Shared\Domain\EmailAddress;
use App\Shared\Domain\Password;
use App\Shared\Domain\Uuid;

trait UserTestsTrait
{
    public function getUser()
    {
        return new User(
            Uuid::create(\Ramsey\Uuid\Uuid::uuid4()->toString()),
            CompleteName::create('test', 'test', 'test'),
            UserPostalAddress::create('address','1','city','03640', 'state'),
            EmailAddress::create('test@domain.com'),
            Password::create('12345678')
        );
    }
}
