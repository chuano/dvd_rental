<?php

declare(strict_types=1);

namespace App\Modules\Rental\User\Domain;

use App\Shared\Domain\EmailAddress;
use App\Shared\Domain\Uuid;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function getById(Uuid $id): ?User;

    public function getByEmail(EmailAddress $emailAddress): ?User;
}
