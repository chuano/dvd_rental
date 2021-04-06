<?php

declare(strict_types=1);

namespace App\Framework\Controller\Frontend\Administration\Users;

use App\Framework\Form\AdminLoginType;
use App\Modules\Administration\AdminUser\Application\Login\AdminLoginRequest;
use App\Modules\Administration\AdminUser\Application\Login\AdminLoginService;
use App\Modules\Administration\AdminUser\Infra\AdminUserRepositoryImpl;
use App\Shared\Infra\JwtServiceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class AdminLoginController extends AbstractController
{
    private AdminUserRepositoryImpl $userRepository;
    private JwtServiceImpl $jwtService;

    public function __construct(AdminUserRepositoryImpl $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->jwtService = new JwtServiceImpl($_SERVER['APP_SECRET']);
    }

    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function login(Request $request): Response
    {
        $loginRequest = new AdminLoginRequest();
        $form = $this->createForm(AdminLoginType::class, $loginRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $loginService = new AdminLoginService($this->userRepository, $this->jwtService);
                $response = $loginService->execute($loginRequest);

                $httpResponse = new RedirectResponse('/admin/');
                $httpResponse->headers->setCookie(new Cookie('token', $response->getData()['token']));
                return $httpResponse;
            } catch (Throwable $e) {
                $this->addFlash('error', 'Login error: ' . $e->getMessage());
            }
        }

        return $this->render(
            'frontend/administration/login.html.twig',
            [
                'loginForm' => $form->createView(),
                'logged' => false,
            ]
        );
    }
}
