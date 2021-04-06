<?php

declare(strict_types=1);

namespace App\Tests\Shared\Domain;

use App\Shared\Domain\CompleteName;
use PHPUnit\Framework\TestCase;

class CompleteNameTest extends TestCase
{
    /** @test */
    public function should_return_concatenated_names()
    {
        $completeName = CompleteName::create('name', 'firstSurname', 'secondSurname');
        $this->assertEquals('name firstSurname secondSurname', $completeName->__toString());
    }

    /** @test */
    public function should_return_trimed_concatenated_names()
    {
        $completeName = CompleteName::create('', 'firstSurname', ' secondSurname ');
        $this->assertEquals('firstSurname secondSurname', $completeName->__toString());
    }
}
