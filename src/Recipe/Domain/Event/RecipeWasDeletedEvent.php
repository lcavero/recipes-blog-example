<?php

namespace App\Recipe\Domain\Event;

use App\Shared\Domain\Event\AbstractDomainEvent;

class RecipeWasDeletedEvent extends AbstractDomainEvent
{
    const EVENT_NAME = 'RecipeWasDeleted';

    private function __construct(
        private string $id,
        private string $name,
        private ?string $description,
        private array $ingredients
    )
    {
        $this->ingredients = json_decode(json_encode($ingredients), true);
        parent::__construct(self::EVENT_NAME);
    }

    public static function create(string $id, string $name, ?string $description, array $ingredients): static
    {
        return new static($id, $name, $description, $ingredients);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function ingredients(): array
    {
        return $this->ingredients;
    }
}
