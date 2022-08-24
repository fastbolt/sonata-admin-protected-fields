<?php

/**
 * @noinspection PhpUndefinedClassInspection
 * @noinspection PhpPropertyOnlyWrittenInspection
 */

namespace Fastbolt\SonataAdminProtectedFields\Tests\Unit\_Fixtures\Mapping;

use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\DeleteProtected;
use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\WriteProtected;

#[DeleteProtected]
class ClassWithProtectedFieldsAndDeleteProtection
{
    public function __construct(
        #[WriteProtected] private bool $propProtected,
        private bool $propUnprotected,
        #[WriteProtected] private bool $prop2Protected,
        #[SomeUnknownAttribute] private bool $prop3Unprotected
    ) {
    }
}
