<?php

declare(strict_types=1);

namespace App\Modules\Administration\Sale\Domain;

use App\Shared\Domain\CompleteName;
use App\Shared\Domain\Uuid;
use DateTimeImmutable;

class Sale
{
    private Uuid $id;
    private MovieTitle $title;
    private Uuid $movieId;
    private CompleteName $completeName;
    private Uuid $customerId;
    private DateTimeImmutable $date;

    public function __construct(
        Uuid $id,
        MovieTitle $title,
        Uuid $movieId,
        CompleteName $completeName,
        Uuid $customerId,
        DateTimeImmutable $date
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->movieId = $movieId;
        $this->completeName = $completeName;
        $this->customerId = $customerId;
        $this->date = $date;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTitle(): MovieTitle
    {
        return $this->title;
    }

    public function getMovieId(): Uuid
    {
        return $this->movieId;
    }

    public function getCompleteName(): CompleteName
    {
        return $this->completeName;
    }

    public function getCustomerId(): Uuid
    {
        return $this->customerId;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
}
