<?php

namespace App\Shared\Infrastructure\EventListener;

use App\Shared\Infrastructure\Exception\BadRequestHttpException;
use App\Shared\Infrastructure\Kernel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class HttpExceptionListener
{

    public function __construct(private Kernel $kernel)
    {}

    public function onKernelException(ExceptionEvent $event)
    {
        if ('prod' !== $this->kernel->getEnvironment()) {
            return;
        }

        $exception = $event->getThrowable();

        if ($exception instanceof HttpExceptionInterface) {
            $response = new JsonResponse();
            $response->setStatusCode($exception->getStatusCode());
            $content = [
                'code' => $exception->getStatusCode(),
                'message' => $exception->getMessage()
            ];

            if ($exception instanceof BadRequestHttpException) {
                $content['errors'] = $exception->errors();
            }

            $response->setContent(json_encode($content));
            $event->setResponse($response);
        }
    }
}
