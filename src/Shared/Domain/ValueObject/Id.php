<?php

namespace App\Shared\Domain\ValueObject;

class Id
{
    private function __construct(private string $id)
    {}

    public static function fromString(string $id): static
    {
        return new static($id);
    }

    public function value(): string
    {
        return $this->id;
    }

    public function toString(): string
    {
        return $this->id;
    }

    public function equals(Id $id): bool
    {
        return $this->id === $id->value();
    }
}
