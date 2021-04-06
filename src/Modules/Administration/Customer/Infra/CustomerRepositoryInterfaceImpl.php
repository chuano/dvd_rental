<?php

declare(strict_types=1);

namespace App\Modules\Administration\Customer\Infra;

use App\Modules\Administration\Customer\Domain\Customer;
use App\Modules\Administration\Customer\Domain\CustomerRepositoryInterface;
use App\Shared\Domain\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class CustomerRepositoryInterfaceImpl implements CustomerRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Customer $customer): void
    {
        $this->entityManager->persist($customer);
        $this->entityManager->flush();
    }

    public function getById(Uuid $id): ?Customer
    {
        return $this->entityManager->getRepository(Customer::class)->find($id);
    }

    /**
     * @return Customer[]
     */
    public function getAll(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        return $this->entityManager
            ->getRepository(Customer::class)
            ->findBy([], ['completeName.firstSurname' => 'ASC'], $limit, $offset);
    }

    public function count(): int
    {
        try {
            return (int)$this->entityManager
                ->getRepository(Customer::class)
                ->createQueryBuilder('customer')
                ->select('count(customer.id)')
                ->getQuery()->getSingleScalarResult();
        } catch (Exception) {
            return 0;
        }
    }
}
