<?php

namespace Fastbolt\SonataAdminProtectedFields\Mapping\Attributes;

use Attribute;
use Fastbolt\SonataAdminProtectedFields\Protection\Checker\PropertyProtectionChecker;

#[Attribute(Attribute::TARGET_PROPERTY)]
class WriteProtected
{
    private string $checker;

    public function __construct(string $checker = PropertyProtectionChecker::class, bool $throwOnMissing = true)
    {
        $this->checker        = $checker;
        $this->throwOnMissing = $throwOnMissing;
    }

    public function getChecker(): string
    {
        return $this->checker;
    }

    public function getThrowOnMissing(): bool
    {
        return $this->throwOnMissing;
    }
}
