<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields\Form;

use Fastbolt\SonataAdminProtectedFields\Exception\FieldNotFoundException;
use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\WriteProtected;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\FormBuilderInterface;

class FormFieldProvider
{
    public function getFormField(
        FormMapper $mapper,
        string $fieldName,
        WriteProtected $writeProtected
    ): ?FormBuilderInterface {
        if (null !== ($field = $this->getFieldDefinition($mapper, $fieldName))) {
            return $field;
        }

        if (!$writeProtected->getThrowOnMissing()) {
            return null;
        }

        $modelClass = $mapper->getAdmin()
                             ->getClass();

        throw FieldNotFoundException::create($fieldName, $modelClass);
    }

    private function getFieldDefinition(FormMapper $mapper, string $fieldName): ?FormBuilderInterface
    {
        if ($mapper->has($fieldName)) {
            return $mapper->get($fieldName);
        }

        // We MUST NOT use FormMapperInterface::getAdmin()::getFormBuilder() here, otherwise
        // we'll end up in recursion.
        $fields = $mapper->getFormBuilder()
                         ->all();

        $fieldsFiltered = array_filter($fields, static function (FormBuilderInterface $field) use ($fieldName) {
            return 0 === stripos($field->getName(), $fieldName);
        });

        foreach ($fieldsFiltered as $field) {
            $path = $field->getPropertyPath();
            if (null !== $path && $path->getElement(0) === $fieldName) {
                return $field;
            }
        }

        return null;
    }
}
