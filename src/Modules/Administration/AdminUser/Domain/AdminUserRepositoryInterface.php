<?php


namespace App\Modules\Administration\AdminUser\Domain;


use App\Shared\Domain\EmailAddress;
use App\Shared\Domain\Uuid;

interface AdminUserRepositoryInterface
{
    public function save(AdminUser $user): void;

    public function getByEmail(EmailAddress $emailAddress): ?AdminUser;

    public function getById(Uuid $id): ?AdminUser;
}
