<?php

namespace Fastbolt\SonataAdminProtectedFields\EventSubscriber;

use Exception;
use Fastbolt\SonataAdminProtectedFields\Mapping\Driver\AnnotationDriver;
use Sonata\AdminBundle\Event\ConfigureEvent;
use Sonata\AdminBundle\Mapper\MapperInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormBuilder;

class FormProtectingEventSubscriber implements EventSubscriberInterface
{
    private AnnotationDriver $driver;

    public function __construct(AnnotationDriver $driver)
    {
        $this->driver = $driver;
    }

    public static function getSubscribedEvents()
    {
        return [
            'sonata.admin.event.configure.form' => 'configureForm',
        ];
    }

    public function configureForm(ConfigureEvent $event): void
    {
        $mapper = $event->getMapper();

        // we try to set "isProtected" to disabled for all admins if field exists, but allow to fail silently.
        $this->protect($mapper, 'isProtected', false);

        $admin      = $event->getAdmin();
        $modelClass = $admin->getModelClass();

        foreach ($this->getProtectedFields($modelClass) as $field) {
            $this->protect($mapper, $field);
        }
    }

    private function protect(MapperInterface $mapper, string $fieldName, bool $throw = true): void
    {
        if (!$mapper->has($fieldName)) {
            if (false === $throw) {
                return;
            }

            $modelClass = $mapper->getAdmin()
                                 ->getModelClass();
            throw new Exception(sprintf('Field %s is not found in mapper for class %s', $fieldName, $modelClass));
        }

        /** @var FormBuilder $field */
        $field = $mapper->get($fieldName);
        $field->setDisabled(true);
    }

    private function getProtectedFields(string $modelClass)
    {
        dd($this->driver->getProtectedFields($modelClass));

        throw new Exception('Not implemented');
    }
}