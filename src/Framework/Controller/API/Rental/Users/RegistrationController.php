<?php

declare(strict_types=1);

namespace App\Framework\Controller\API\Rental\Users;

use App\Framework\Form\RegistrationType;
use App\Modules\Rental\User\Application\Registration\RegistrationRequest;
use App\Modules\Rental\User\Application\Registration\RegistrationService;
use App\Modules\Rental\User\Infra\UserRepositoryImpl;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RegistrationController extends AbstractController
{
    private UserRepositoryImpl $userRepository;
    private SerializerInterface $serializer;

    public function __construct(UserRepositoryImpl $userRepostory, SerializerInterface $serializer)
    {
        $this->userRepository = $userRepostory;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/rental/users", methods={"POST"})
     */
    public function register(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $uuid = Uuid::uuid4()->toString();

        $registrationRequest = new RegistrationRequest();
        $registrationRequest->setUuid($uuid);
        $form = $this->createForm(RegistrationType::class, $registrationRequest);
        $form->submit($data);

        $registrationService = new RegistrationService($this->userRepository);
        $response = $registrationService->execute($registrationRequest);

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($response->getData(), 'json'),
            Response::HTTP_CREATED
        );
    }
}
