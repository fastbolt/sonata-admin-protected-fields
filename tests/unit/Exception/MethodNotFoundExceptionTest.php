<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields\Tests\Unit\Exception;

use Fastbolt\SonataAdminProtectedFields\Exception\MethodNotFoundException;
use Fastbolt\TestHelpers\BaseTestCase;

/**
 * @covers \Fastbolt\SonataAdminProtectedFields\Exception\MethodNotFoundException
 */
class MethodNotFoundExceptionTest extends BaseTestCase
{
    public function testItem(): void
    {
        $exception = MethodNotFoundException::create('methodName', 'className');

        self::assertSame('Method methodName not found in class className', $exception->getMessage());
    }
}
