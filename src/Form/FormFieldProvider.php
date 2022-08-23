<?php

namespace Fastbolt\SonataAdminProtectedFields\Form;

use Fastbolt\SonataAdminProtectedFields\Exception\FieldNotFoundException;
use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\WriteProtected;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;

class FormFieldProvider
{

    public function getFormField(
        FormMapper $mapper,
        string $fieldName,
        WriteProtected $writeProtected
    ): ?FormBuilderInterface {
        if ($mapper->has($fieldName)) {
            return $mapper->get($fieldName);
        }

        if (null !== ($field = $this->getFieldDefinition($mapper, $fieldName))) {
            return $field;
        }

        if (!$writeProtected->getThrowOnMissing()) {
            return null;
        }

        $modelClass = $mapper->getAdmin()
                             ->getModelClass();

        throw FieldNotFoundException::create($fieldName, $modelClass);
    }

    private function getFieldDefinition(FormMapper $mapper, string $fieldName): ?FormBuilderInterface
    {
        $fields = $mapper->getFormBuilder()
                         ->all();

        $fieldsFiltered = array_filter($fields, static function (FormBuilder $field) use ($fieldName) {
            return 0 === stripos($field->getName(), $fieldName);
        });

        foreach ($fieldsFiltered as $field) {
            $path = $field->getPropertyPath();
            if ($path->getElement(0) === $fieldName) {
                return $field;
            }
        }

        return null;
    }
}