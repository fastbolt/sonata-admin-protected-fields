<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields\Tests\Unit\Mapping\Driver;

use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\DeleteProtected;
use Fastbolt\SonataAdminProtectedFields\Mapping\Driver\AttributeDriver;
use Fastbolt\SonataAdminProtectedFields\Tests\Unit\_Fixtures\Mapping\ClassWithoutDeleteProtection;
use Fastbolt\SonataAdminProtectedFields\Tests\Unit\_Fixtures\Mapping\ClassWithProtectedFieldsAndDeleteProtection;
use Fastbolt\TestHelpers\BaseTestCase;

/**
 * @covers \Fastbolt\SonataAdminProtectedFields\Mapping\Driver\AttributeDriver
 */
class AttributeDriverTest extends BaseTestCase
{
    public function testGetProtectedFields()
    {
        $driver = new AttributeDriver();

        $result = $driver->getProtectedFields(ClassWithProtectedFieldsAndDeleteProtection::class);

        self::assertCount(2, $result);
        self::assertSame(['propProtected', 'prop2Protected'], array_keys($result));
    }

    public function testDeleteProtectionProtected()
    {
        $driver = new AttributeDriver();

        $result = $driver->getDeleteProtection(ClassWithProtectedFieldsAndDeleteProtection::class);

        self::assertInstanceOf(DeleteProtected::class, $result);
    }

    public function testDeleteProtectionNotProtected()
    {
        $driver = new AttributeDriver();

        $result = $driver->getDeleteProtection(ClassWithoutDeleteProtection::class);

        self::assertNull($result);
    }
}
