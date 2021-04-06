<?php

declare(strict_types=1);

namespace App\Framework\Controller\Frontend\Administration\Users;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class AdminLogoutController
{
    /**
     * @Route("/admin/logout", name="admin_logout")
     */
    public function logout()
    {
        $response = new RedirectResponse('/login');
        $response->headers->clearCookie('token');
        return $response;
    }
}
