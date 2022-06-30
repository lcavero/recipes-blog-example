<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Validation\DomainValidationTrait;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class Id
{
    use DomainValidationTrait;

    private Uuid $id;

    private function __construct(string $id)
    {
        $this->validate(['id' => $id]);
        $this->id = Uuid::fromString($id);
    }

    private function validationConstrains(): Assert\Collection
    {
        return new Assert\Collection([
            'id'    => [
                new Assert\NotBlank(),
                new Assert\Length(0, 1, 255)
            ],
        ]);
    }

    public static function fromString(string $id): static
    {
        return new static($id);
    }

    public function value(): Uuid
    {
        return $this->id;
    }

    public function toString(): string
    {
        return $this->id->toBase32();
    }

    public function equals(Id $id): bool
    {
        return $this->id === $id->value();
    }
}
