<?php

namespace Fastbolt\SonataAdminProtectedFields\Protection;

use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\WriteProtected;
use Fastbolt\SonataAdminProtectedFields\Protection\Checker\Checker;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Mapper\MapperInterface;

class DefaultProtector
{
    private array $checkers = [];

    private \Fastbolt\SonataAdminProtectedFields\Form\FormFieldProvider $formFieldProvider;

    /**
     * @param iterable<Checker> $checkers
     */
    public function __construct(
        \Fastbolt\SonataAdminProtectedFields\Form\FormFieldProvider $fieldProvider,
        iterable $checkers
    ) {
        $this->formFieldProvider = $fieldProvider;

        foreach ($checkers as $checker) {
            $this->checkers[$checker::class] = $checker;
        }
    }

    public function protect(FormMapper $mapper, iterable $protectedFields)
    {
        foreach ($protectedFields as $fieldName => $configuration) {
            $this->protectField($mapper, $fieldName, $configuration);
        }
    }

    private function protectField(FormMapper $mapper, string $fieldName, WriteProtected $field): void
    {
        if (null === ($formField = $this->formFieldProvider->getFormField($mapper, $fieldName, $field))) {
            return;
        }

        $formField->setDisabled(true);
    }
}
