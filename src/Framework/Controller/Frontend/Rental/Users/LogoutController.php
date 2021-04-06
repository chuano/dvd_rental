<?php

declare(strict_types=1);

namespace App\Framework\Controller\Frontend\Rental\Users;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController
{
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        $response = new RedirectResponse('/login');
        $response->headers->clearCookie('token');
        return $response;
    }
}
