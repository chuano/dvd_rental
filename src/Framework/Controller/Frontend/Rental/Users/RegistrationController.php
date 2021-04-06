<?php

declare(strict_types=1);

namespace App\Framework\Controller\Frontend\Rental\Users;

use App\Framework\Form\RegistrationType;
use App\Modules\Rental\User\Application\Registration\RegistrationRequest;
use App\Modules\Rental\User\Application\Registration\RegistrationService;
use App\Modules\Rental\User\Infra\UserRepositoryImpl;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class RegistrationController extends AbstractController
{
    private UserRepositoryImpl $userRepository;

    public function __construct(UserRepositoryImpl $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/register", name="register")
     */
    public function registration(Request $request): Response
    {
        $uuid = Uuid::uuid4()->toString();

        $registrationRequest = new RegistrationRequest();
        $registrationRequest->setUuid($uuid);
        $form = $this->createForm(RegistrationType::class, $registrationRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $registrationService = new RegistrationService($this->userRepository);
                $registrationService->execute($registrationRequest);

                $this->addFlash('success', 'User registered successfully');
                return new RedirectResponse('/login');
            } catch (Throwable) {
                $this->addFlash('error', 'Registration error');
            }
        }

        return $this->render(
            'frontend/rental/register.html.twig',
            [
                'registerForm' => $form->createView(),
                'logged' => false,
            ]
        );
    }
}
