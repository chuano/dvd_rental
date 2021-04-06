<?php

declare(strict_types=1);

namespace App\Framework\Event\Subscribers;

use App\Modules\Administration\Customer\Domain\Customer;
use App\Modules\Administration\Customer\Infra\CustomerRepositoryInterfaceImpl;
use App\Modules\Rental\User\Domain\Event\UserCreated;
use Doctrine\ORM\EntityManagerInterface;

class CreateCustomerSubscriber
{
    private CustomerRepositoryInterfaceImpl $customerRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->customerRepository = new CustomerRepositoryInterfaceImpl($entityManager);
    }

    public function execute(UserCreated $event): void
    {
        $customer = new Customer($event->getPayload()->getId(), $event->getPayload()->getCompleteName());
        $this->customerRepository->save($customer);
    }
}
