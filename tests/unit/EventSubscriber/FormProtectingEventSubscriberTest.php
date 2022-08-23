<?php

namespace Fastbolt\SonataAdminProtectedFields\Tests\Unit\EventSubscriber;

use Fastbolt\SonataAdminProtectedFields\EventSubscriber\FormProtectingEventSubscriber;
use Fastbolt\SonataAdminProtectedFields\Mapping\Driver\AttributeDriver;
use Fastbolt\SonataAdminProtectedFields\Protection\DefaultProtector;
use Fastbolt\SonataAdminProtectedFields\Tests\Unit\_fixtures\DummyType;
use Fastbolt\TestHelpers\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Event\ConfigureEvent;
use Sonata\AdminBundle\Mapper\MapperInterface;

/**
 * @covers \Fastbolt\SonataAdminProtectedFields\EventSubscriber\FormProtectingEventSubscriber
 */
class FormProtectingEventSubscriberTest extends BaseTestCase
{
    /**
     * @var AttributeDriver&MockObject
     */
    private $driver;

    /**
     * @var DefaultProtector&MockObject
     */
    private $protector;

    /**
     * @var MapperInterface&MockObject
     */
    private $mapper;

    /**
     * @var AbstractAdmin&MockObject
     */
    private $admin;

    public function testGetSubscribedEvents(): void
    {
        $this->assertEquals(
            [
                'sonata.admin.event.configure.form' => 'configureForm',
            ],
            FormProtectingEventSubscriber::getSubscribedEvents()
        );
    }

    public function testConfigureForm(): void
    {
        $event      = new ConfigureEvent($this->admin, $this->mapper, '\App\Foo\BarAdmin');
        $subscriber = new FormProtectingEventSubscriber($this->driver, $this->protector);
        $subject    = new DummyType(false);
        $this->admin->setModelClass(DummyType::class);
        $this->admin->setSubject($subject);
        $this->driver->expects(self::once())
                     ->method('getProtectedFields')
                     ->with(DummyType::class)
                     ->willReturn($fields = ['foo', 'bar']);

        $this->protector->expects(self::once())
                        ->method('protectForm')
                        ->with($this->mapper, $fields, $subject);

        $subscriber->configureForm($event);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->driver    = $this->getMock(AttributeDriver::class);
        $this->protector = $this->getMock(DefaultProtector::class);
        $this->mapper    = $this->getMock(MapperInterface::class);
        $this->admin     = $this->getMock(AbstractAdmin::class);
    }
}
