<?php

declare(strict_types=1);

namespace App\Tests\Shared\Domain;

use App\Shared\Domain\Password;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    /** @test */
    public function should_encrypt_password()
    {
        $password = Password::create('12345678');
        $this->assertNotEquals('12345678', $password->getValue());
    }
}
