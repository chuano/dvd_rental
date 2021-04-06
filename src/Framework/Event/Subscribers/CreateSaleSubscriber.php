<?php

declare(strict_types=1);

namespace App\Framework\Event\Subscribers;

use App\Modules\Administration\Customer\Infra\CustomerRepositoryInterfaceImpl;
use App\Modules\Administration\Sale\Domain\MovieTitle;
use App\Modules\Administration\Sale\Domain\Sale;
use App\Modules\Administration\Sale\Infra\SaleRepositoryImpl;
use App\Modules\Rental\RentalOrder\Domain\Event\RentalOrderCreated;
use App\Shared\Movie\Infra\MovieRepositoryImpl;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class CreateSaleSubscriber
{
    private SaleRepositoryImpl $saleRepository;
    private MovieRepositoryImpl $movieRepository;
    private CustomerRepositoryInterfaceImpl $customerRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->saleRepository = new SaleRepositoryImpl($entityManager);
        $this->movieRepository = new MovieRepositoryImpl($entityManager);
        $this->customerRepository = new CustomerRepositoryInterfaceImpl($entityManager);
    }

    public function execute(RentalOrderCreated $event): void
    {
        $id = $event->getPayload()->getId();
        $movie = $this->movieRepository->getById($event->getPayload()->getMovieId());
        $title = new MovieTitle($movie->getMetadata()->getTitle());
        $movieId = $movie->getId();
        $customer = $this->customerRepository->getById($event->getPayload()->getUserId());
        $customerId = $event->getPayload()->getUserId();
        $sale = new Sale($id, $title, $movieId, $customer->getCompleteName(), $customerId, new DateTimeImmutable());
        $this->saleRepository->save($sale);
    }
}
