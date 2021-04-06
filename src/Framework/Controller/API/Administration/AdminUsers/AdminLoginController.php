<?php

declare(strict_types=1);

namespace App\Framework\Controller\API\Administration\AdminUsers;

use App\Framework\Form\AdminLoginType;
use App\Modules\Administration\AdminUser\Application\Login\AdminLoginRequest;
use App\Modules\Administration\AdminUser\Application\Login\AdminLoginService;
use App\Modules\Administration\AdminUser\Infra\AdminUserRepositoryImpl;
use App\Shared\Infra\JwtServiceImpl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AdminLoginController extends AbstractController
{
    private JwtServiceImpl $jwtService;

    public function __construct(
        private AdminUserRepositoryImpl $userRepository,
        private SerializerInterface $serializer
    ) {
        $this->jwtService = new JwtServiceImpl($_SERVER['APP_SECRET']);
    }

    /**
     * @Route("/api/administration/users/tokens", methods={"POST"})
     */
    public function login(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $loginRequest = new AdminLoginRequest();
        $form = $this->createForm(AdminLoginType::class, $loginRequest);
        $form->submit($data);

        $loginService = new AdminLoginService($this->userRepository, $this->jwtService);
        $response = $loginService->execute($loginRequest);

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($response->getData(), 'json'),
            Response::HTTP_OK
        );
    }
}
