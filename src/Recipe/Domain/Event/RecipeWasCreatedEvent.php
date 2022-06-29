<?php

namespace App\Recipe\Domain\Event;

use App\Shared\Domain\Event\AbstractDomainEvent;

class RecipeWasCreatedEvent extends AbstractDomainEvent
{
    const EVENT_NAME = 'RecipeWasCreated';

    private function __construct(private string $id, private string $name)
    {
        parent::__construct(self::EVENT_NAME);
    }

    public static function create(string $id, string $name): static
    {
        return new static($id, $name);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
