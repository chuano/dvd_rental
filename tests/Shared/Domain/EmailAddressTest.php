<?php

declare(strict_types=1);

namespace App\Tests\Shared\Domain;

use App\Shared\Domain\EmailAddress;
use App\Shared\Domain\Exception\InvalidEmailException;
use PHPUnit\Framework\TestCase;

class EmailAddressTest extends TestCase
{
    /** @test */
    public function should_throw_invalid_email_exception_given_email_without_domain()
    {
        $this->expectException(InvalidEmailException::class);
        EmailAddress::create('invalidemail');
    }

    /** @test */
    public function should_throw_invalid_email_exception_given_email_without_name()
    {
        $this->expectException(InvalidEmailException::class);
        EmailAddress::create('@invalidemail.com');
    }

    /** @test */
    public function should_throw_invalid_email_exception_given_email_without_termination()
    {
        $this->expectException(InvalidEmailException::class);
        EmailAddress::create('invalid@email');
    }
}
