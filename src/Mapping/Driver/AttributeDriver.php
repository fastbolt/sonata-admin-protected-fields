<?php

namespace Fastbolt\SonataAdminProtectedFields\Mapping\Driver;

use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\DeleteProtected;
use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\WriteProtected;
use ReflectionAttribute;
use ReflectionClass;

class AttributeDriver
{
    /**
     * Attribute to identify field as being write protected
     */
    public const WRITE_PROTECTED = WriteProtected::class;

    /**
     * Attribute to identify field as being delete protected
     */
    public const DELETE_PROTECTED = DeleteProtected::class;

    public function getProtectedFields(string $modelClass): array
    {
        $reflectionClass = new ReflectionClass($modelClass);
        $properties      = $reflectionClass->getProperties();
        $protectedFields = [];
        foreach ($properties as $property) {
            $attributes = $property->getAttributes(self::WRITE_PROTECTED);
            if (0 === count($attributes)) {
                continue;
            }
            $name = $property->getName();
            /** @var ReflectionAttribute $attr */
            $attr = end($attributes);

            $protectedFields[$name] = $attr->newInstance();
        }

        return $protectedFields;
    }

    public function getDeleteProtection(string $modelClass): ?DeleteProtected
    {
        $reflectionClass = new ReflectionClass($modelClass);
        if (0 === count($attributes = $reflectionClass->getAttributes(self::DELETE_PROTECTED))) {
            return null;
        }

        return end($attributes)->newInstance();
    }
}
