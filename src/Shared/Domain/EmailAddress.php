<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Exception\InvalidEmailException;

class EmailAddress extends StringValueObject
{
    /**
     * @throws InvalidEmailException
     */
    public static function create(string $email): self
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException();
        }
        return new self($email);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
