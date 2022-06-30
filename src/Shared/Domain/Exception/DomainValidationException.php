<?php

namespace App\Shared\Domain\Exception;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class DomainValidationException extends DomainException implements ValidationException
{
    private ConstraintViolationListInterface $violations;

    private function __construct(ConstraintViolationListInterface $violations)
    {
        $this->violations = $violations;
        parent::__construct('Domain validation failed.');
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
