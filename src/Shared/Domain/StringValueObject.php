<?php

declare(strict_types=1);

namespace App\Shared\Domain;

class StringValueObject
{
    protected string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(StringValueObject $object): bool
    {
        return $object->getValue() === $this->value;
    }
}
