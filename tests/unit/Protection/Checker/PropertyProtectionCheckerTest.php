<?php

namespace Fastbolt\SonataAdminProtectedFields\Tests\Unit\Protection\Checker;

use Fastbolt\SonataAdminProtectedFields\Protection\Checker\PropertyProtectionChecker;
use Fastbolt\SonataAdminProtectedFields\Tests\Unit\_fixtures\DummyType;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Fastbolt\SonataAdminProtectedFields\Protection\Checker\PropertyProtectionChecker
 */
class PropertyProtectionCheckerTest extends TestCase
{
    public function testItem(): void
    {
        $checker = new PropertyProtectionChecker();

        $item = new DummyType(true);
        self::assertTrue($checker->shouldBeProtected($item));

        $item = new DummyType(false);
        self::assertFalse($checker->shouldBeProtected($item));
    }
}
