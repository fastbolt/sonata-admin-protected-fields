<?php

namespace Fastbolt\SonataAdminProtectedFields\Tests\Unit\Protection\Checker;

use Fastbolt\SonataAdminProtectedFields\Protection\Checker\PropertyProtectionChecker;
use Fastbolt\TestHelpers\BaseTestCase;
use stdClass;

/**
 * @covers \Fastbolt\SonataAdminProtectedFields\Protection\Checker\PropertyProtectionChecker
 */
class PropertyProtectionCheckerTest extends BaseTestCase
{
    public function testItem(): void
    {
        $checker = new PropertyProtectionChecker();

        self::assertSame(PropertyProtectionChecker::NAME, $checker->getName());

        $item = $this->getMock(stdClass::class, [], ['isProtected']);
        $item->method('isProtected')
             ->willReturn(true);
        self::assertTrue($checker->shouldBeProtected($item));

        $item = $this->getMock(stdClass::class, [], ['isProtected']);
        $item->method('isProtected')
             ->willReturn(false);
        self::assertFalse($checker->shouldBeProtected($item));
    }
}
