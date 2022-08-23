<?php

namespace Fastbolt\SonataAdminProtectedFields\Tests\Unit\Mapping\Attributes;

use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\DeleteProtected;
use Fastbolt\TestHelpers\BaseTestCase;

/**
 * @covers \Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\DeleteProtected
 */
class DeleteProtectedTest extends BaseTestCase
{
    public function testItem(): void
    {
        $item = new DeleteProtected('fooClassName');

        self::assertSame('fooClassName', $item->getChecker());
    }
}
