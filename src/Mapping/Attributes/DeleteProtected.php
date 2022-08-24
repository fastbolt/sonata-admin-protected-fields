<?php

namespace Fastbolt\SonataAdminProtectedFields\Mapping\Attributes;

use Attribute;
use Fastbolt\SonataAdminProtectedFields\Protection\Checker\PropertyProtectionChecker;

#[Attribute(Attribute::TARGET_CLASS)]
class DeleteProtected
{
    public function __construct(private string $checker = PropertyProtectionChecker::NAME)
    {
    }

    public function getChecker(): string
    {
        return $this->checker;
    }
}
