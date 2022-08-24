<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields\Mapping\Attributes;

use Attribute;
use Fastbolt\SonataAdminProtectedFields\Protection\Checker\PropertyProtectionChecker;

#[Attribute(Attribute::TARGET_PROPERTY)]
class WriteProtected
{
    public function __construct(
        private string $checker = PropertyProtectionChecker::NAME,
        private bool $throwOnMissing = true
    ) {
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
