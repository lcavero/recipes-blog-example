<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\InvalidIdentityException;
use App\Shared\Domain\Validation\DomainValidationTrait;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class Id
{
    use DomainValidationTrait;

    private Uuid $id;

    private function __construct(string $id)
    {
        $this->id = Uuid::fromString($id);
    }

    private function validationConstrains(): Assert\Collection
    {
        return new Assert\Collection([
            'id'    => [
                new Assert\NotBlank(),
                new Assert\Length(0, 1, 255),
            ],
        ]);
    }

    public static function fromString(string $id): static
    {
        try {
            return new static($id);
        } catch (\Exception $e) {
            throw InvalidIdentityException::create(sprintf('Invalid identifier %s received', $id), $e);
        }
    }

    public static function fromId(Id $id): static
    {
        return new static($id->toString());
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
