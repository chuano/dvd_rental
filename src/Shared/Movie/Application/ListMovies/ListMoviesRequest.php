<?php

declare(strict_types=1);

namespace App\Shared\Movie\Application\ListMovies;

class ListMoviesRequest
{
    const DEFAULT_LIMIT = 20;
    const DEFAULT_PAGE = 1;

    private int $page;
    private int $limit;

    public function __construct(int $page, int $limit)
    {
        $this->page = $page;
        $this->limit = $limit;
    }

    public static function create(array $data)
    {
        return new self(
            isset($data['page']) ? (int)$data['page'] : self::DEFAULT_PAGE,
            isset($data['page']) ? (int)$data['page'] : self::DEFAULT_LIMIT
        );
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
