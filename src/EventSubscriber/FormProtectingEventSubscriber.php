<?php

namespace Fastbolt\SonataAdminProtectedFields\EventSubscriber;

use Fastbolt\SonataAdminProtectedFields\Mapping\Driver\AttributeDriver;
use Fastbolt\SonataAdminProtectedFields\Protection\DefaultProtector;
use Sonata\AdminBundle\Event\ConfigureEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FormProtectingEventSubscriber implements EventSubscriberInterface
{
    private AttributeDriver $driver;

    public function __construct(AttributeDriver $driver, DefaultProtector $protector)
    {
        $this->driver    = $driver;
        $this->protector = $protector;
    }

    public static function getSubscribedEvents()
    {
        return [
            'sonata.admin.event.configure.form' => 'configureForm',
        ];
    }

    public function configureForm(ConfigureEvent $event): void
    {
        $mapper     = $event->getMapper();
        $admin      = $event->getAdmin();
        $modelClass = $admin->getModelClass();

        $this->protector->protect($mapper, $this->driver->getProtectedFields($modelClass));
    }
}
