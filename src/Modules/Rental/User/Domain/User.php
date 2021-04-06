<?php

declare(strict_types=1);

namespace App\Modules\Rental\User\Domain;

use App\Modules\Rental\User\Domain\Event\UserCreated;
use App\Shared\Domain\CompleteName;
use App\Shared\Domain\EmailAddress;
use App\Shared\Domain\Event\DomainEventDispatcher;
use App\Shared\Domain\Password;
use App\Shared\Domain\Uuid;

class User
{
    private Uuid $id;
    private CompleteName $completeName;
    private UserPostalAddress $postalAddress;
    private EmailAddress $email;
    private Password $password;

    public function __construct(
        Uuid $id,
        CompleteName $completeName,
        UserPostalAddress $postalAddress,
        EmailAddress $email,
        Password $password
    ) {
        $this->id = $id;
        $this->completeName = $completeName;
        $this->postalAddress = $postalAddress;
        $this->email = $email;
        $this->password = $password;

        DomainEventDispatcher::getInstance()->publish(new UserCreated($this));
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getCompleteName(): CompleteName
    {
        return $this->completeName;
    }

    public function getPostalAddress(): UserPostalAddress
    {
        return $this->postalAddress;
    }

    public function getEmail(): EmailAddress
    {
        return $this->email;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }
}
