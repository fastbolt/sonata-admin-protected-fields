<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields\Exception;

use Exception;

class MethodNotFoundException extends Exception
{
    public function __construct(string $methodName, string $objectClass)
    {
        parent::__construct(
            sprintf(
                'Method %s not found in class %s',
                $methodName,
                $objectClass
            )
        );
    }

    public static function create(string $methodName, string $objectClass): MethodNotFoundException
    {
        return new self($methodName, $objectClass);
    }
}
