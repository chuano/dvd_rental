<?php

declare(strict_types=1);

namespace App\Shared\Domain;

class Password extends StringValueObject
{
    public static function create(string $password): self
    {
        $cryptedPass = password_hash($password, PASSWORD_BCRYPT);
        return new self($cryptedPass);
    }

    public function equal(string $givenPass): bool
    {
        return password_verify($givenPass, $this->value);
    }
}
