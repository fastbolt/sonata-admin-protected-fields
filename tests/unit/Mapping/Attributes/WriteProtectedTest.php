<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields\Tests\Unit\Mapping\Attributes;

use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\WriteProtected;
use Fastbolt\TestHelpers\BaseTestCase;

/**
 * @covers \Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\WriteProtected
 */
class WriteProtectedTest extends BaseTestCase
{
    public function testItem(): void
    {
        $item = new WriteProtected('fooClassName', false);

        self::assertSame('fooClassName', $item->getChecker());
        self::assertFalse($item->getThrowOnMissing());
    }
}
