<?php

declare(strict_types=1);

namespace App\Framework\Controller\Frontend\Rental\Users;

use App\Framework\Form\LoginType;
use App\Modules\Rental\User\Application\Login\LoginRequest;
use App\Modules\Rental\User\Application\Login\LoginService;
use App\Modules\Rental\User\Infra\UserRepositoryImpl;
use App\Shared\Infra\JwtServiceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class LoginController extends AbstractController
{
    private UserRepositoryImpl $userRepository;
    private JwtServiceImpl $jwtService;

    public function __construct(UserRepositoryImpl $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->jwtService = new JwtServiceImpl($_SERVER['APP_SECRET']);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request): Response
    {
        $loginRequest = new LoginRequest();
        $form = $this->createForm(LoginType::class, $loginRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $loginService = new LoginService($this->userRepository, $this->jwtService);
                $response = $loginService->execute($loginRequest);

                $httpResponse = new RedirectResponse('/');
                $httpResponse->headers->setCookie(new Cookie('token', $response->getData()['token']));
                return $httpResponse;
            } catch (Throwable $e) {
                $this->addFlash('error', 'Login error: ' . $e->getMessage());
            }
        }

        return $this->render(
            'frontend/rental/login.html.twig',
            [
                'loginForm' => $form->createView(),
                'logged' => false,
            ]
        );
    }
}
