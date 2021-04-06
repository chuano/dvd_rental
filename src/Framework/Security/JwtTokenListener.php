<?php

declare(strict_types=1);

namespace App\Framework\Security;

use App\Modules\Administration\AdminUser\Infra\AdminUserRepositoryImpl;
use App\Modules\Rental\User\Domain\Exception\InvalidTokenException;
use App\Shared\Infra\JwtServiceImpl;
use App\Modules\Rental\User\Infra\UserRepositoryImpl;
use App\Shared\Domain\Credentials;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Throwable;

class JwtTokenListener
{
    private JwtServiceImpl $jwtService;
    private UserRepositoryImpl $userRepository;
    private AdminUserRepositoryImpl $adminUserRepository;

    public function __construct(UserRepositoryImpl $userRepository, AdminUserRepositoryImpl $adminUserRepository)
    {
        $this->jwtService = new JwtServiceImpl($_SERVER['APP_SECRET']);
        $this->userRepository = $userRepository;
        $this->adminUserRepository = $adminUserRepository;
    }

    /**
     * @throws InvalidTokenException
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        $token = $this->getToken($event->getRequest());
        $credentials = $this->getCredentials($token);

        // Exception for api
        if (!$credentials && $controller[0] instanceof JwtAuthenticatedInterface) {
            throw new InvalidTokenException();
        }

        // Redirection for frontend
        if (!$credentials && $controller[0] instanceof JwtFrontentdAuthenticatedInterface) {
            $event->setController(fn() => new RedirectResponse('/login'));
        }

        // Redirection for frontend backoffice
        if (!$credentials && $controller[0] instanceof JwtFrontentdAdminAuthenticatedInterface) {
            $event->setController(fn() => new RedirectResponse('/admin/login'));
        }

        // Avoid users access to backoffice
        if (
            $credentials &&
            $credentials->getProfile() !== Credentials::ADMIN_PROFILE &&
            $controller[0] instanceof JwtFrontentdAdminAuthenticatedInterface
        ) {
            $event->setController(fn() => new RedirectResponse('/admin/login'));
        }

        $event->getRequest()->server->set('user', $credentials);
    }

    private function getToken(Request $request): ?string
    {
        $token = $request->headers->get('authorization');
        if (!$token) {
            $token = $request->query->get('token');
        }
        if (!$token) {
            $token = $request->cookies->get('token');
        }
        return str_replace('Bearer ', '', $token ?? '');
    }

    private function getCredentials(?string $token = null): ?Credentials
    {
        try {
            $credentials = $this->jwtService->decode($token);
            if ($credentials->getProfile() === Credentials::USER_PROFILE) {
                $user = $this->userRepository->getById($credentials->getId());
            } else {
                $user = $this->adminUserRepository->getById($credentials->getId());
            }
            if (!$user) {
                throw new InvalidTokenException();
            }
            return $credentials;
        } catch (Throwable) {
            return null;
        }
    }
}
