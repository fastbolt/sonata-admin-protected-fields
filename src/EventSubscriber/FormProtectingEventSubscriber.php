<?php


namespace EventSubscriber;

use App\Security\EntityProtectionInterface;
use Sonata\AdminBundle\Event\ConfigureEvent;
use Sonata\AdminBundle\Mapper\MapperInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormEvents;

class FormProtectingEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA            => 'preSetData',
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
        if (!is_subclass_of($modelClass, EntityProtectionInterface::class)) {
            return;
        }

        foreach ($modelClass::getProtectedFields() as $field) {
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
}