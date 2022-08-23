<?php

namespace Fastbolt\SonataAdminProtectedFields\Form;

use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\FormBuilder;

class FormFieldProvider
{

    public function getFormField(
        FormMapper $mapper,
        string $fieldName,
        \Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\WriteProtected $writeProtected
    ): ?\Symfony\Component\Form\FormBuilderInterface {
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

        throw new \Exception(sprintf('Field %s not found in mapper for class %s.', $fieldName, $modelClass));
    }

    private function getFieldDefinition(FormMapper $mapper, string $fieldName)
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