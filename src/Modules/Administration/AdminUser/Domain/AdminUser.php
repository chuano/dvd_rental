<?php

declare(strict_types=1);

namespace App\Modules\Administration\AdminUser\Domain;

use App\Shared\Domain\EmailAddress;
use App\Shared\Domain\Password;
use App\Shared\Domain\Uuid;

class AdminUser
{
    private Uuid $id;
    private EmailAddress $email;
    private Password $password;

    public function __construct(Uuid $id, EmailAddress $email, Password $password)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getEmailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }
}
