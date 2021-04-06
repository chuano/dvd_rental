<?php

declare(strict_types=1);

namespace App\Modules\Administration\AdminUser\Application\Login;

use App\Modules\Administration\AdminUser\Domain\AdminUserRepositoryInterface;
use App\Shared\Domain\Exception\InvalidCredentialsException;
use App\Shared\Domain\JwtService;
use App\Shared\Domain\Credentials;
use App\Shared\Domain\EmailAddress;
use App\Shared\Domain\Exception\InvalidEmailException;

class AdminLoginService
{
    private AdminUserRepositoryInterface $userRepository;
    private JwtService $jwtService;

    public function __construct(AdminUserRepositoryInterface $userRepository, JwtService $jwtService)
    {
        $this->userRepository = $userRepository;
        $this->jwtService = $jwtService;
    }

    /**
     * @throws InvalidEmailException|InvalidCredentialsException
     */
    public function execute(AdminLoginRequest $request): AdminLoginResponse
    {
        $email =  EmailAddress::create($request->getEmail());
        $user = $this->userRepository->getByEmail($email);
        if (!$user) {
            throw new InvalidCredentialsException();
        }
        if (!$user->getPassword()->equal($request->getPassword())) {
            throw new InvalidCredentialsException();
        }
        $token = $this->jwtService->encode(new Credentials($user->getId(), Credentials::ADMIN_PROFILE));

        return new AdminLoginResponse($token);
    }
}
