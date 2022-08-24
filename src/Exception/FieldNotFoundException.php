<?php

namespace Fastbolt\SonataAdminProtectedFields\Exception;

use Exception;

class FieldNotFoundException extends Exception
{
    public function __construct(string $fieldName, string $objectClass)
    {
        parent::__construct(
            sprintf(
                'Field %s not found in class %s',
                $fieldName,
                $objectClass
            )
        );
    }

    public static function create(string $fieldName, string $objectClass): FieldNotFoundException
    {
        return new self($fieldName, $objectClass);
    }
}
