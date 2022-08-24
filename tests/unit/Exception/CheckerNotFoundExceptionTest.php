<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields\Tests\Unit\Exception;

use Fastbolt\SonataAdminProtectedFields\Exception\CheckerNotFoundException;
use Fastbolt\TestHelpers\BaseTestCase;

/**
 * @covers \Fastbolt\SonataAdminProtectedFields\Exception\CheckerNotFoundException
 */
class CheckerNotFoundExceptionTest extends BaseTestCase
{
    public function testItem(): void
    {
        $exception = CheckerNotFoundException::create('checkerName');

        self::assertSame('Checker not found: checkerName', $exception->getMessage());
    }
}
