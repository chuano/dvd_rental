<?php

declare(strict_types=1);

namespace App\Modules\Rental\RentalOrder\Infra;

use App\Modules\Rental\RentalOrder\Domain\RentalOrder;
use App\Modules\Rental\RentalOrder\Domain\RentalOrderRepositoryInterface;
use App\Modules\Rental\RentalOrder\Domain\RentalStatus;
use App\Shared\Domain\Uuid;
use Doctrine\ORM\EntityManagerInterface;

class RentalOrderRepositoryImpl implements RentalOrderRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(RentalOrder $rentalOrder): void
    {
        $this->entityManager->persist($rentalOrder);
        $this->entityManager->flush();
    }

    public function getById(Uuid $id): ?RentalOrder
    {
        return $this->entityManager->getRepository(RentalOrder::class)->find($id);
    }

    public function getByUserId(Uuid $userId): array
    {
        return $this->entityManager
            ->getRepository(RentalOrder::class)
            ->findBy(
                [
                    'userId' => $userId,
                    'status.status' => RentalStatus::ACTIVE
                ]
            );
    }

    public function getByUserIdAndMovieId(Uuid $userId, Uuid $movieId): array
    {
        return $this->entityManager
            ->getRepository(RentalOrder::class)
            ->findBy(
                [
                    'userId' => $userId,
                    'movieId' => $movieId,
                    'status.status' => RentalStatus::ACTIVE
                ]
            );
    }
}
