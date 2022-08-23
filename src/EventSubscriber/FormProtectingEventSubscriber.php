<?php

namespace Fastbolt\SonataAdminProtectedFields\EventSubscriber;

use Fastbolt\SonataAdminProtectedFields\Mapping\Driver\AttributeDriver;
use Fastbolt\SonataAdminProtectedFields\Protection\DefaultProtector;
use Sonata\AdminBundle\Event\ConfigureEvent;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FormProtectingEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private AttributeDriver $driver, private DefaultProtector $protector)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            'sonata.admin.event.configure.form' => 'configureForm',
        ];
    }

    public function configureForm(ConfigureEvent $event): void
    {
        /** @var FormMapper $mapper */
        $mapper     = $event->getMapper();
        $admin      = $event->getAdmin();
        $modelClass = $admin->getModelClass();
        $object     = $admin->getSubject();

        $this->protector->protectForm($mapper, $this->driver->getProtectedFields($modelClass), $object);
    }
}
