<?php

namespace Fastbolt\SonataAdminProtectedFields\Exception;

use Exception;

class CheckerNotFoundException extends Exception
{
    public function __construct(string $checkerName)
    {
        parent::__construct(
            sprintf(
                'Checker not found: %s',
                $checkerName
            )
        );
    }

    public static function create(string $checkerName): CheckerNotFoundException
    {
        return new self($checkerName);
    }
}
