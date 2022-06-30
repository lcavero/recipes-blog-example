<?php

namespace App\Shared\Application\Exception;

use App\Shared\Domain\Exception\ValidationException;
use Exception;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class MessageValidationException extends Exception implements ValidationException
{
    private ConstraintViolationListInterface $violations;

    private function __construct(ConstraintViolationListInterface $violations)
    {
        $this->violations = $violations;
        parent::__construct('Message validation failed.');
    }

    public static function fromViolations(ConstraintViolationListInterface $violations): static
    {
        return new static($violations);
    }

    public function getMessages(): array
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $messages = [];

        foreach ($this->violations as $violation) {
            $value   = $accessor->getValue($messages, $violation->getPropertyPath()) ?? [];
            $value[] = $violation->getMessage();
            $accessor->setValue($messages, $violation->getPropertyPath(), $value);
        }

        return $messages;
    }
}
