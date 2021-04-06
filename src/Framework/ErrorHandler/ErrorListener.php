<?php

declare(strict_types=1);

namespace App\Framework\ErrorHandler;

use App\Shared\Infra\ErrorService;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ErrorListener
{
    private ErrorService $errorService;

    public function __construct(ErrorService $errorService)
    {
        $this->errorService = $errorService;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $event->setResponse($this->errorService->format($event->getThrowable()));
    }
}
