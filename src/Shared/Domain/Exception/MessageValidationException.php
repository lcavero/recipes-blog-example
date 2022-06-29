<?php

namespace App\Shared\Domain\Exception;

use DomainException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class MessageValidationException extends DomainException
{
    private ConstraintViolationListInterface $violations;

    public function __construct(ConstraintViolationListInterface $violations)
    {
        $this->violations = $violations;
        parent::__construct('Message validation failed.');
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
