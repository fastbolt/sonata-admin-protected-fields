<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields\Tests\Unit\Protection\EntitySecurityHandler;

use Fastbolt\SonataAdminProtectedFields\Exception\CheckerNotFoundException;
use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\DeleteProtected;
use Fastbolt\SonataAdminProtectedFields\Mapping\Driver\AttributeDriver;
use Fastbolt\SonataAdminProtectedFields\Protection\Checker\Checker;
use Fastbolt\SonataAdminProtectedFields\Protection\EntitySecurityHandler\ProtectingSecurityHandler;
use Fastbolt\TestHelpers\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Security\Handler\SecurityHandlerInterface;
use stdClass;

/**
 * @covers \Fastbolt\SonataAdminProtectedFields\Protection\EntitySecurityHandler\ProtectingSecurityHandler
 */
class ProtectingSecurityHandlerTest extends BaseTestCase
{
    /**
     * @var SecurityHandlerInterface&MockObject
     */
    private $parent;

    /**
     * @var AttributeDriver&MockObject
     */
    private $driver;

    /**
     * @var Checker&MockObject
     */
    private $checker;

    /**
     * @var AdminInterface&MockObject
     */
    private $admin;

    public function testGetBaseRoleParent(): void
    {
        $handler = new ProtectingSecurityHandler($this->parent, $this->driver, [$this->checker]);

        $this->parent->expects(self::once())
                     ->method('getBaseRole')
                     ->with($this->admin)
                     ->willReturn('fooBaseRole');
        $result = $handler->getBaseRole($this->admin);

        self::assertSame('fooBaseRole', $result);
    }

    public function testBuildSecurityInformationParent(): void
    {
        $handler = new ProtectingSecurityHandler($this->parent, $this->driver, [$this->checker]);

        $this->parent->expects(self::once())
                     ->method('buildSecurityInformation')
                     ->with($this->admin)
                     ->willReturn($info = ['foo' => ['foo1'], 'bar' => ['bar2'], 'array' => ['arr3']]);
        $result = $handler->buildSecurityInformation($this->admin);

        self::assertSame($info, $result);
    }

    public function testCreateObjectSecurityParent(): void
    {
        $handler = new ProtectingSecurityHandler($this->parent, $this->driver, [$this->checker]);
        $object  = new stdClass();
        $this->parent->expects(self::once())
                     ->method('createObjectSecurity')
                     ->with($this->admin, $object);
        $handler->createObjectSecurity($this->admin, $object);
    }

    public function testDeleteObjectSecurityParent(): void
    {
        $handler = new ProtectingSecurityHandler($this->parent, $this->driver, [$this->checker]);
        $object  = new stdClass();
        $this->parent->expects(self::once())
                     ->method('deleteObjectSecurity')
                     ->with($this->admin, $object);
        $handler->deleteObjectSecurity($this->admin, $object);
    }

    public function testIsGrantedParentReturnsFalse(): void
    {
        $this->checker->expects(self::never())
                      ->method('shouldBeProtected');
        $this->driver->expects(self::never())
                     ->method('getDeleteProtection');
        $handler    = new ProtectingSecurityHandler($this->parent, $this->driver, [$this->checker]);
        $object     = new stdClass();
        $attributes = 'attributes';
        $this->parent->expects(self::once())
                     ->method('isGranted')
                     ->with($this->admin, $attributes, $object)
                     ->willReturn(false);
        $result = $handler->isGranted($this->admin, $attributes, $object);

        self::assertFalse($result);
    }

    public function testIsGrantedNoObject(): void
    {
        $this->checker->expects(self::never())
                      ->method('shouldBeProtected');
        $this->driver->expects(self::never())
                     ->method('getDeleteProtection');
        $handler    = new ProtectingSecurityHandler($this->parent, $this->driver, [$this->checker]);
        $object     = null;
        $attributes = 'attributes';
        $this->parent->expects(self::once())
                     ->method('isGranted')
                     ->willReturn(true);
        $result = $handler->isGranted($this->admin, $attributes, $object);

        self::assertTrue($result);
    }

    public function testIsGrantedWrongAttribute(): void
    {
        $this->checker->expects(self::never())
                      ->method('shouldBeProtected');
        $this->driver->expects(self::never())
                     ->method('getDeleteProtection');
        $handler    = new ProtectingSecurityHandler($this->parent, $this->driver, [$this->checker]);
        $object     = new stdClass();
        $attributes = 'attributes';
        $this->parent->expects(self::once())
                     ->method('isGranted')
                     ->willReturn(true);
        $result = $handler->isGranted($this->admin, $attributes, $object);

        self::assertTrue($result);
    }

    public function testIsGrantedNoDeleteProtection(): void
    {
        $this->checker->expects(self::never())
                      ->method('shouldBeProtected');
        $this->driver->expects(self::once())
                     ->method('getDeleteProtection')
                     ->with(stdClass::class)
                     ->willReturn(null);
        $handler    = new ProtectingSecurityHandler($this->parent, $this->driver, [$this->checker]);
        $object     = new stdClass();
        $attributes = 'DELETE';
        $this->parent->expects(self::once())
                     ->method('isGranted')
                     ->willReturn(true);
        $result = $handler->isGranted($this->admin, $attributes, $object);

        self::assertTrue($result);
    }

    public function testIsGrantedWithDeleteProtectionShouldBeProtected(): void
    {
        $object = new stdClass();
        $this->checker->expects(self::once())
                      ->method('shouldBeProtected')
                      ->with($object)
                      ->willReturn(true);
        $this->driver->expects(self::once())
                     ->method('getDeleteProtection')
                     ->with(stdClass::class)
                     ->willReturn(new DeleteProtected('fooChecker'));
        $handler    = new ProtectingSecurityHandler($this->parent, $this->driver, [$this->checker]);
        $attributes = 'DELETE';
        $this->parent->expects(self::once())
                     ->method('isGranted')
                     ->willReturn(true);
        $result = $handler->isGranted($this->admin, $attributes, $object);

        self::assertFalse($result);
    }

    public function testIsGrantedWithDeleteProtectionShouldNotBeProtected(): void
    {
        $object = new stdClass();
        $this->checker->expects(self::once())
                      ->method('shouldBeProtected')
                      ->with($object)
                      ->willReturn(false);
        $this->driver->expects(self::once())
                     ->method('getDeleteProtection')
                     ->with(stdClass::class)
                     ->willReturn(new DeleteProtected('fooChecker'));
        $handler    = new ProtectingSecurityHandler($this->parent, $this->driver, [$this->checker]);
        $attributes = 'DELETE';
        $this->parent->expects(self::once())
                     ->method('isGranted')
                     ->willReturn(true);
        $result = $handler->isGranted($this->admin, $attributes, $object);

        self::assertTrue($result);
    }

    public function testIsGrantedWithDeleteProtectionCheckerNotFound(): void
    {
        $object = new stdClass();
        $this->checker->expects(self::never())
                      ->method('shouldBeProtected');
        $this->driver->expects(self::once())
                     ->method('getDeleteProtection')
                     ->with(stdClass::class)
                     ->willReturn(new DeleteProtected('asdfChecker'));
        $handler    = new ProtectingSecurityHandler($this->parent, $this->driver, [$this->checker]);
        $attributes = 'DELETE';
        $this->parent->expects(self::once())
                     ->method('isGranted')
                     ->willReturn(true);

        $this->expectException(CheckerNotFoundException::class);
        $this->expectExceptionMessage('Checker not found: asdfChecker');

        $handler->isGranted($this->admin, $attributes, $object);
    }

    protected function setUp(): void
    {
        $this->parent  = $this->createMock(SecurityHandlerInterface::class);
        $this->driver  = $this->createMock(AttributeDriver::class);
        $this->checker = $this->createMock(Checker::class);
        $this->checker->method('getName')
                      ->willReturn('fooChecker');
        $this->admin = $this->getMock(AdminInterface::class);
    }
}
