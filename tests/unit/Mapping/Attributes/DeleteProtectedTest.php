<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
