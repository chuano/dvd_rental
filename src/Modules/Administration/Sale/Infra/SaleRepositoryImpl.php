<?php

declare(strict_types=1);

namespace App\Modules\Administration\Sale\Infra;

use App\Modules\Administration\Sale\Domain\Sale;
use App\Modules\Administration\Sale\Domain\SaleRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class SaleRepositoryImpl implements SaleRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Sale $sale): void
    {
        $this->entityManager->persist($sale);
        $this->entityManager->flush();
    }

    /**
     * @return Sale[]
     */
    public function getAll(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        return $this->entityManager
            ->getRepository(Sale::class)
            ->findBy([], ['date' => 'ASC'], $limit, $offset);
    }

    /**
     * @throws NoResultException|NonUniqueResultException
     */
    public function count(): int
    {
        return (int)$this->entityManager
            ->getRepository(Sale::class)
            ->createQueryBuilder('sale')
            ->select('count(sale.id)')
            ->getQuery()->getSingleScalarResult();
    }
}
