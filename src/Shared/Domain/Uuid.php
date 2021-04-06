<?php

declare(strict_types=1);

namespace App\Shared\Domain;

class Uuid extends StringValueObject
{
    public static function create(string $value): self
    {
        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
