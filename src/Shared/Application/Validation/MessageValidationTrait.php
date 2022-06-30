<?php

namespace App\Shared\Application\Validation;

use App\Shared\Application\Exception\MessageValidationException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

trait MessageValidationTrait
{
    public function validate(array $payload): void
    {
        $validator  = Validation::createValidator();
        $violations = $validator->validate($payload, $this->validationConstrains());
        if (count($violations) !== 0) {
            throw MessageValidationException::fromViolations($violations);
        }
    }

    abstract protected function validationConstrains(): Assert\Collection;
}
