<?php

declare(strict_types=1);

namespace App\Modules\Rental\User\Domain\Event;

use App\Modules\Rental\User\Domain\User;
use App\Shared\Domain\Event\DomainEventInterface;
use DateTimeImmutable;

class UserCreated implements DomainEventInterface
{
    public const NAME = 'user.created';

    private User $user;
    private DateTimeImmutable $ocurredOn;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->ocurredOn = new DateTimeImmutable();
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getOcurredOn(): DateTimeImmutable
    {
        return $this->ocurredOn;
    }

    public function getPayload(): User
    {
        return $this->user;
    }
}
