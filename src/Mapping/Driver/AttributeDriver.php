<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields\Mapping\Driver;

use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\DeleteProtected;
use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\WriteProtected;
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

    /**
     * @param class-string $modelClass
     *
     * @return array<string,WriteProtected>
     */
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
            $name                   = $property->getName();
            $protectedFields[$name] = $attributes[0]->newInstance();
        }

        return $protectedFields;
    }

    /**
     * @param class-string $modelClass
     *
     * @return DeleteProtected|null
     */
    public function getDeleteProtection(string $modelClass): ?DeleteProtected
    {
        $reflectionClass = new ReflectionClass($modelClass);
        if (0 === count($attributes = $reflectionClass->getAttributes(self::DELETE_PROTECTED))) {
            return null;
        }

        return $attributes[0]->newInstance();
    }
}
