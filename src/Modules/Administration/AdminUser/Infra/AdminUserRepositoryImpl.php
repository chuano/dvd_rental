<?php

declare(strict_types=1);

namespace App\Modules\Administration\AdminUser\Infra;

use App\Modules\Administration\AdminUser\Domain\AdminUser;
use App\Modules\Administration\AdminUser\Domain\AdminUserRepositoryInterface;
use App\Modules\Rental\User\Domain\User;
use App\Shared\Domain\EmailAddress;
use App\Shared\Domain\Uuid;
use Doctrine\ORM\EntityManagerInterface;

class AdminUserRepositoryImpl implements AdminUserRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(AdminUser $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function getById(Uuid $id): ?AdminUser
    {
        return $this->entityManager->getRepository(AdminUser::class)->find($id);
    }

    public function getByEmail(EmailAddress $emailAddress): ?AdminUser
    {
        return $this->entityManager
            ->getRepository(AdminUser::class)
            ->findOneBy(
                [
                    'email.value' => $emailAddress->getValue()
                ]
            );
    }
}
