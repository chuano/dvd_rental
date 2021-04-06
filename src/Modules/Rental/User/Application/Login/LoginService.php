<?php

declare(strict_types=1);

namespace App\Modules\Rental\User\Application\Login;

use App\Shared\Domain\JwtService;
use App\Modules\Rental\User\Domain\UserRepositoryInterface;
use App\Shared\Domain\Credentials;
use App\Shared\Domain\EmailAddress;
use App\Shared\Domain\Exception\InvalidEmailException;

class LoginService
{
    private UserRepositoryInterface $userRepository;
    private JwtService $jwtService;

    public function __construct(UserRepositoryInterface $userRepository, JwtService $jwtService)
    {
        $this->userRepository = $userRepository;
        $this->jwtService = $jwtService;
    }

    /**
     * @throws InvalidEmailException|InvalidCredentialsException
     */
    public function execute(LoginRequest $request): LoginResponse
    {
        $email =  EmailAddress::create($request->getEmail());
        $user = $this->userRepository->getByEmail($email);
        if (!$user) {
            throw new InvalidCredentialsException();
        }
        if (!$user->getPassword()->equal($request->getPassword())) {
            throw new InvalidCredentialsException();
        }
        $token = $this->jwtService->encode(new Credentials($user->getId(), Credentials::USER_PROFILE));

        return new LoginResponse($token);
    }
}
