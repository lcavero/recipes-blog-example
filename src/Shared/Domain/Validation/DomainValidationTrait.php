<?php

namespace App\Shared\Domain\Validation;

use App\Shared\Domain\Exception\DomainValidationException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

trait DomainValidationTrait
{
    public function validate(array $payload): void
    {
        $validator  = Validation::createValidator();
        $violations = $validator->validate($payload, $this->validationConstrains());
        if (count($violations) !== 0) {
            throw DomainValidationException::fromViolations($violations);
        }
    }

    abstract protected function validationConstrains(): Assert\Collection;
}
