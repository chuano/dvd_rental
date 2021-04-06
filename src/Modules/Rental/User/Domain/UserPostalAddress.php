<?php

declare(strict_types=1);

namespace App\Modules\Rental\User\Domain;

class UserPostalAddress
{
    private string $address;
    private string $number;
    private string $city;
    private string $zipCode;
    private string $state;

    private function __construct(
        string $address,
        string $number,
        string $city,
        string $zipCode,
        string $state
    ) {
        $this->address = $address;
        $this->number = $number;
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->state = $state;
    }

    public static function create(string $address, string $number, string $city, string $zipCode, string $state): self
    {
        return new self($address, $number, $city, $zipCode, $state);
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function getState(): string
    {
        return $this->state;
    }
}
