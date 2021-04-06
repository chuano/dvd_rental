<?php

declare(strict_types=1);

namespace App\Shared\Domain;

class CompleteName
{
    private string $firstName;
    private string $firstSurname;
    private string $secondSurname;

    private function __construct(string $firstName, string $firstSurname, string $secondSurname)
    {
        $this->firstName = $firstName;
        $this->firstSurname = $firstSurname;
        $this->secondSurname = $secondSurname;
    }

    public static function create(string $firstName, string $firstSurname, string $secondSurname): self
    {
        return new self($firstName, $firstSurname, $secondSurname);
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getFirstSurname(): string
    {
        return $this->firstSurname;
    }

    public function getSecondSurname(): string
    {
        return $this->secondSurname;
    }

    public function __toString(): string
    {
        return trim(trim($this->firstName) . ' ' . trim($this->firstSurname) . ' ' . trim($this->secondSurname));
    }
}
