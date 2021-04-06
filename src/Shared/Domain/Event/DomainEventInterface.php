<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

use DateTimeImmutable;

interface DomainEventInterface
{
    public function getName(): string;

    public function getOcurredOn(): DateTimeImmutable;

    public function getPayload(): object;
}
