<?php

declare(strict_types=1);

namespace App\Modules\Administration\Customer\Domain;

use App\Shared\Domain\CompleteName;
use App\Shared\Domain\Uuid;

class Customer
{
    private Uuid $id;
    private CompleteName $completeName;

    public function __construct(Uuid $id, CompleteName $completeName)
    {
        $this->id = $id;
        $this->completeName = $completeName;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getCompleteName(): CompleteName
    {
        return $this->completeName;
    }
}
