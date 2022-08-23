<?php

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
