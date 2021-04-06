<?php

declare(strict_types=1);

namespace App\Modules\Rental\User\Application\Registration;

class RegistrationRequest
{
    private string $uuid;
    private string $firstName;
    private string $firstSurname;
    private string $secondSurname;
    private string $address;
    private string $number;
    private string $city;
    private string $zipCode;
    private string $state;
    private string $email;
    private string $password;

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getFirstSurname(): string
    {
        return $this->firstSurname;
    }

    public function setFirstSurname(string $firstSurname): void
    {
        $this->firstSurname = $firstSurname;
    }

    public function getSecondSurname(): string
    {
        return $this->secondSurname;
    }

    public function setSecondSurname(string $secondSurname): void
    {
        $this->secondSurname = $secondSurname;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

}
