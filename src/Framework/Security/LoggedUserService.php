<?php

declare(strict_types=1);

namespace App\Framework\Security;

use App\Shared\Domain\Credentials;
use Symfony\Component\HttpFoundation\Request;

class LoggedUserService
{
    public function getCredentials(Request $request): Credentials
    {
        return $request->server->get('user');
    }
}
