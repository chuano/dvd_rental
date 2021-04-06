<?php

declare(strict_types=1);

namespace App\Modules\Rental\User\Infra;

use App\Modules\Rental\User\Domain\User;
use App\Modules\Rental\User\Domain\UserRepositoryInterface;
use App\Shared\Domain\EmailAddress;
use App\Shared\Domain\Uuid;
use Doctrine\ORM\EntityManagerInterface;

class UserRepositoryImpl implements UserRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function getById(Uuid $id): ?User
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }

    public function getByEmail(EmailAddress $emailAddress): ?User
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(
                [
                    'email.value' => $emailAddress->getValue()
                ]
            );
    }
}
