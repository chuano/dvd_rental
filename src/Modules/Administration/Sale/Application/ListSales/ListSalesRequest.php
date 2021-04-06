<?php

declare(strict_types=1);

namespace App\Modules\Administration\Sale\Application\ListSales;

class ListSalesRequest
{
    const DEFAULT_LIMIT = 20;
    const DEFAULT_PAGE = 1;

    private int $page;
    private int $limit;
    private int $userProfile;

    public function __construct(int $page, int $limit, int $userProfile)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->userProfile = $userProfile;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getUserProfile(): int
    {
        return $this->userProfile;
    }
}
