<?php

declare(strict_types=1);

namespace App\Framework\Controller\Frontend\Administration;

use App\Framework\Security\JwtFrontentdAdminAuthenticatedInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController implements JwtFrontentdAdminAuthenticatedInterface
{
    /**
     * @Route("/admin/", name="admin_home")
     */
    public function home()
    {
        return $this->render('frontend/administration/home.html.twig',['logged' => true]);
    }
}
