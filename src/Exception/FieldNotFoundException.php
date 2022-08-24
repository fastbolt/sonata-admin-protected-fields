<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
