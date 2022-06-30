<?php

namespace App\Shared\Domain\Exception;

interface ValidationException
{
    public function getMessages(): array;
}
