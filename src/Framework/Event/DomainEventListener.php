<?php

declare(strict_types=1);

namespace App\Framework\Event;

use App\Framework\Event\Subscribers\CreateCustomerSubscriber;
use App\Framework\Event\Subscribers\CreateSaleSubscriber;
use App\Framework\Event\Subscribers\DecreaseMovieStockSubscriber;
use App\Framework\Event\Subscribers\IncreaseMovieStockSubscriber;
use App\Modules\Rental\RentalOrder\Domain\Event\RentalOrderCreated;
use App\Modules\Rental\RentalOrder\Domain\Event\RentalOrderFinished;
use App\Modules\Rental\User\Domain\Event\UserCreated;
use App\Shared\Domain\Event\DomainEventDispatcher;
use App\Shared\Domain\Event\DomainEventInterface;
use Doctrine\ORM\EntityManagerInterface;

class DomainEventListener
{
    private array $subscribers = [];

    public function __construct(EntityManagerInterface $entityManager)
    {
        // Initialize domain event subscribers
        $this->subscribers = [
            UserCreated::NAME => [
                new CreateCustomerSubscriber($entityManager),
            ],
            RentalOrderCreated::NAME => [
                new DecreaseMovieStockSubscriber($entityManager),
                new CreateSaleSubscriber($entityManager),
            ],
            RentalOrderFinished::NAME => [
                new IncreaseMovieStockSubscriber($entityManager),
            ],
        ];
    }

    public function onKernelFinishRequest()
    {
        /** @var DomainEventInterface[] $events */
        $events = DomainEventDispatcher::getInstance()->getEvents();
        foreach ($events as $event) {
            if (isset($this->subscribers[$event->getName()])) {
                // Execute subscribers
                foreach ($this->subscribers[$event->getName()] as $subscriber) {
                    $subscriber->execute($event);
                }
            }
        }
        DomainEventDispatcher::getInstance()->clearEvents();
    }
}
