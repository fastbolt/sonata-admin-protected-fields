<?php

namespace Fastbolt\SonataAdminProtectedFields\Protection;

use Fastbolt\SonataAdminProtectedFields\Exception\CheckerNotFoundException;
use Fastbolt\SonataAdminProtectedFields\Form\FormFieldProvider;
use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\WriteProtected;
use Fastbolt\SonataAdminProtectedFields\Protection\Checker\Checker;
use Sonata\AdminBundle\Mapper\MapperInterface;

class DefaultProtector
{
    /**
     * @var array<class-string,Checker>
     */
    private array $checkers = [];

    private FormFieldProvider $formFieldProvider;

    /**
     * @param array<class-string,Checker> $checkers
     */
    public function __construct(
        FormFieldProvider $fieldProvider,
        array $checkers
    ) {
        $this->formFieldProvider = $fieldProvider;

        foreach ($checkers as $checker) {
            $this->checkers[$checker::class] = $checker;
        }
    }

    public function protectForm(MapperInterface $mapper, iterable $protectedFields, object $object): void
    {
        foreach ($protectedFields as $fieldName => $configuration) {
            $this->protectField($mapper, $fieldName, $configuration, $object);
        }
    }

    private function protectField(
        MapperInterface $mapper,
        string $fieldName,
        WriteProtected $writeProtected,
        object $object
    ): void {
        if (null === ($formField = $this->formFieldProvider->getFormField($mapper, $fieldName, $writeProtected))) {
            return;
        }

        $checkerName = $writeProtected->getChecker();
        if (null === ($checker = $this->checkers[$checkerName] ?? null)) {
            throw CheckerNotFoundException::create($checkerName);
        }

        if (true === $checker->shouldBeProtected($object)) {
            $formField->setDisabled(true);
        }
    }
}
