<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

    public static function getSubscribedEvents(): array
    {
        return [
            'sonata.admin.event.configure.form' => 'configureForm',
        ];
    }

    public function configureForm(ConfigureEvent $event): void
    {
        /** @psalm-var FormMapper $mapper */
        $mapper = $event->getMapper();
        $admin  = $event->getAdmin();
        /** @psalm-var class-string $modelClass */
        $modelClass = $admin->getModelClass();
        $object     = $admin->getSubject();

        $this->protector->protectForm($mapper, $this->driver->getProtectedFields($modelClass), $object);
    }
}
