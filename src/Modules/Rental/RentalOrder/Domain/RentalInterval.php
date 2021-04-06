<?php

declare(strict_types=1);

namespace App\Modules\Rental\RentalOrder\Domain;

use DateTimeImmutable;

class RentalInterval
{
    private DateTimeImmutable $from;
    private DateTimeImmutable $to;

    public function __construct(DateTimeImmutable $from, DateTimeImmutable $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function getFrom(): DateTimeImmutable
    {
        return $this->from;
    }

    public function getTo(): DateTimeImmutable
    {
        return $this->to;
    }
}
