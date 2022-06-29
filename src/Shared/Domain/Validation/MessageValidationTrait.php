<?php

namespace App\Shared\Domain\Validation;

use App\Shared\Domain\Exception\MessageValidationException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use function count;

trait MessageValidationTrait
{
    public function validate(array $payload): void
    {
        $validator  = Validation::createValidator();
        $violations = $validator->validate($payload, $this->validationConstrains());
        if (count($violations) !== 0) {
            throw new MessageValidationException($violations);
        }
    }

    abstract protected function validationConstrains() : Assert\Collection;
}
