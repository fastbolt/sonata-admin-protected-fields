<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields\Tests\Unit\_Fixtures\Mapping;

use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\DeleteProtected;
use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\WriteProtected;

#[DeleteProtected]
class ClassWithProtectedFieldsAndDeleteProtection
{
    /**
     * @psalm-suppress UndefinedAttributeClass
     */
    public function __construct(
        #[WriteProtected] private bool $propProtected,
        private bool $propUnprotected,
        #[WriteProtected] private bool $prop2Protected,
        #[SomeUnknownAttribute] private bool $prop3Unprotected
    ) {
    }
}
