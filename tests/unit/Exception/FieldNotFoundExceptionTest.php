<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields\Tests\Unit\Exception;

use Fastbolt\SonataAdminProtectedFields\Exception\FieldNotFoundException;
use Fastbolt\TestHelpers\BaseTestCase;

/**
 * @covers \Fastbolt\SonataAdminProtectedFields\Exception\FieldNotFoundException
 */
class FieldNotFoundExceptionTest extends BaseTestCase
{
    public function testItem(): void
    {
        $exception = FieldNotFoundException::create('fieldName', 'className');

        self::assertSame('Field fieldName not found in class className', $exception->getMessage());
    }
}
