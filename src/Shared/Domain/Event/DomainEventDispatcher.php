<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

class DomainEventDispatcher
{
    private static ?self $instance = null;
    private array $events;

    private function __construct()
    {
        $this->events = [];
    }

    public static function getInstance(): self
    {
        if (null === static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    public function publish(DomainEventInterface $event): void
    {
        $this->events[] = $event;
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function clearEvents(): void
    {
        $this->events = [];
    }
}
