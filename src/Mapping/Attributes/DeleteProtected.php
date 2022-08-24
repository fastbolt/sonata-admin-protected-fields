<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
