<?php

declare(strict_types=1);

namespace App\Framework\Controller\API\Rental\Users;

use App\Framework\Form\LoginType;
use App\Modules\Rental\User\Application\Login\LoginRequest;
use App\Modules\Rental\User\Application\Login\LoginService;
use App\Modules\Rental\User\Infra\UserRepositoryImpl;
use App\Shared\Infra\JwtServiceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class LoginController extends AbstractController
{
    private JwtServiceImpl $jwtService;

    public function __construct(
        private UserRepositoryImpl $userRepository,
        private SerializerInterface $serializer
    ) {
        $this->jwtService = new JwtServiceImpl($_SERVER['APP_SECRET']);
    }

    /**
     * @Route("/api/rental/users/tokens", methods={"POST"})
     */
    public function login(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $loginRequest = new LoginRequest();
        $form = $this->createForm(LoginType::class, $loginRequest);
        $form->submit($data);

        $loginService = new LoginService($this->userRepository, $this->jwtService);
        $response = $loginService->execute($loginRequest);

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($response->getData(), 'json'),
            Response::HTTP_OK
        );
    }
}
