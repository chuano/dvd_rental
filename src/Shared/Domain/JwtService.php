<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Credentials;

interface JwtService
{
    public function __construct(string $secret);

    public function encode(Credentials $credentials): string;

    public function decode(string $jwt): Credentials;
}
