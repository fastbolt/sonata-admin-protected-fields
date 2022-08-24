<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields\Tests\Unit\EventSubscriber;

use Fastbolt\SonataAdminProtectedFields\EventSubscriber\FormProtectingEventSubscriber;
use Fastbolt\SonataAdminProtectedFields\Mapping\Driver\AttributeDriver;
use Fastbolt\SonataAdminProtectedFields\Protection\DefaultProtector;
use Fastbolt\TestHelpers\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Builder\FormContractorInterface;
use Sonata\AdminBundle\Event\ConfigureEvent;
use Sonata\AdminBundle\Form\FormMapper;
use stdClass;
use Symfony\Component\Form\FormBuilder;

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
     * @var AbstractAdmin&MockObject
     */
    private $admin;

    /**
     * @var FormContractorInterface&MockObject
     */
    private $formContractor;

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
        $mapper     = new FormMapper($this->formContractor, $this->builder, $this->admin);
        $event      = new ConfigureEvent($this->admin, $mapper, '\App\Foo\BarAdmin');
        $subscriber = new FormProtectingEventSubscriber($this->driver, $this->protector);
        $subject    = $this->getMock(stdClass::class, [], ['isProtected'], 'fooBarType');
        $subject->method('isProtected')
                ->willReturn(false);
        $this->admin->setModelClass('fooBarType');
        $this->admin->setSubject($subject);
        $this->driver->expects(self::once())
                     ->method('getProtectedFields')
                     ->with('fooBarType')
                     ->willReturn($fields = ['foo', 'bar']);

        $this->protector->expects(self::once())
                        ->method('protectForm')
                        ->with($mapper, $fields, $subject);

        $subscriber->configureForm($event);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->driver         = $this->getMock(AttributeDriver::class);
        $this->protector      = $this->getMock(DefaultProtector::class);
        $this->admin          = $this->getMock(AbstractAdmin::class);
        $this->builder        = $this->getMock(FormBuilder::class);
        $this->formContractor = $this->getMock(FormContractorInterface::class);
    }
}
