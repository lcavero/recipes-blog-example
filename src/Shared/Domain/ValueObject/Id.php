<?php

namespace App\Shared\Domain\ValueObject;

use Webmozart\Assert\Assert;

class Id
{
    private string $id;

    private function __construct(string $id)
    {
        Assert::lengthBetween($id, 1, 255);
        $this->id = $id;
    }

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
