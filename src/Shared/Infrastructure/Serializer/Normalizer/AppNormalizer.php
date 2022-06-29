<?php

namespace App\Shared\Infrastructure\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AppNormalizer implements NormalizerInterface
{
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        $reflectionClass = new \ReflectionClass($object);

        $data = [];
        foreach ($reflectionClass->getProperties() as $property){
            $propertyName = $property->getName();
            if ($property->isPublic()) {
                $data[$propertyName] = $object->$propertyName;
                continue;
            }
            if ($reflectionClass->hasMethod($propertyName)) {
                $data[$propertyName] = $object->$propertyName();
            }
        }

        return $data;
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return __CLASS__ === static::class;
    }
}
