<?php

namespace App\Shared\Domain\Identity;

use App\Shared\Domain\ValueObject\Id;
use Symfony\Component\Uid\Uuid;

class IdFactory
{
    public static function create(): Id
    {
        return Id::fromString(Uuid::v4());
    }
}
