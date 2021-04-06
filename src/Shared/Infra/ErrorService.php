<?php

declare(strict_types=1);

namespace App\Shared\Infra;

use App\Shared\Domain\Exception\CustomException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ErrorService
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function format(Throwable $throwable): Response
    {
        $this->logger->error($throwable->getMessage());
        $this->logger->error($throwable->getTraceAsString());

        if ($throwable instanceof CustomException) {
            return new JsonResponse(
                [
                    'error' => [
                        'message' => $throwable->getMessage()
                    ]
                ],
                $throwable->getCode()
            );
        }

        return new JsonResponse(
            [
                'error' => [
                    'message' => 'Internal server error'
                ]
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
