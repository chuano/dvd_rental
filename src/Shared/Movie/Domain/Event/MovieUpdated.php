<?php

declare(strict_types=1);

namespace App\Shared\Movie\Domain\Event;

use App\Shared\Domain\Event\DomainEventInterface;
use App\Shared\Movie\Domain\Movie;
use DateTimeImmutable;

class MovieUpdated implements DomainEventInterface
{
    public const NAME = 'movie.updated';

    private Movie $movie;
    private DateTimeImmutable $ocurredOn;

    public function __construct(Movie $movie)
    {
        $this->movie = $movie;
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

    public function getPayload(): Movie
    {
        return $this->movie;
    }


}
