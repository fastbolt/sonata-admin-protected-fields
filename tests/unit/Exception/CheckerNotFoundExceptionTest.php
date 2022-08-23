<?php

namespace Fastbolt\SonataAdminProtectedFields\Tests\Unit\Exception;

use Fastbolt\SonataAdminProtectedFields\Exception\CheckerNotFoundException;
use Fastbolt\TestHelpers\BaseTestCase;
use PHPUnit\Framework\TestCase;

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
