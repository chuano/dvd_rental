<?php

declare(strict_types=1);

namespace App\Modules\Rental\User\Application\Registration;

use App\Modules\Rental\User\Domain\User;
use App\Modules\Rental\User\Domain\UserPostalAddress;
use App\Modules\Rental\User\Domain\UserRepositoryInterface;
use App\Shared\Domain\CompleteName;
use App\Shared\Domain\EmailAddress;
use App\Shared\Domain\Exception\InvalidEmailException;
use App\Shared\Domain\Password;
use App\Shared\Domain\Uuid;

class RegistrationService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws InvalidEmailException|DuplicatedEmailException
     */
    public function execute(RegistrationRequest $request): RegistrationResponse
    {
        $user = $this->buildUserFromRequest($request);
        $this->ensureUniqueEmail($user);
        $this->userRepository->save($user);

        return new RegistrationResponse($user);
    }

    /**
     * @throws InvalidEmailException
     */
    private function buildUserFromRequest(RegistrationRequest $request): User
    {
        $uuid = Uuid::create($request->getUuid());
        $completeName = CompleteName::create(
            $request->getFirstName(),
            $request->getFirstSurname(),
            $request->getSecondSurname()
        );
        $postalAddress = UserPostalAddress::create(
            $request->getAddress(),
            $request->getNumber(),
            $request->getCity(),
            $request->getZipCode(),
            $request->getState()
        );
        $email = EmailAddress::create($request->getEmail());
        $password = Password::create($request->getPassword());

        return new User($uuid, $completeName, $postalAddress, $email, $password);
    }

    /**
     * @throws DuplicatedEmailException
     */
    private function ensureUniqueEmail(User $user)
    {
        $userWithSameEmail = $this->userRepository->getByEmail($user->getEmail());
        if ($userWithSameEmail) {
            throw new DuplicatedEmailException();
        }
    }
}
