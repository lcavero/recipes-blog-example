<?php

namespace App\Shared\Infrastructure\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BadRequestHttpException extends HttpException
{
    private array $errors;

    private function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct(Response::HTTP_BAD_REQUEST, 'Invalid request');
    }

    public static function fromErrors(array $errors): static
    {
        return new static($errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
