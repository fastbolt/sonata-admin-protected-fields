<?php

namespace Annotations\Driver;

use Annotations\DeleteProtected;
use Annotations\WriteProtected;
use Gedmo\Mapping\Driver\AbstractAnnotationDriver;

class Annotation extends AbstractAnnotationDriver
{
    /**
     * Annotation to identify field as translatable
     */
    public const WRITE_PROTECTED = WriteProtected::class;

    public const DELETE_PROTECTED = DeleteProtected::class;

    public function readExtendedMetadata($meta, array &$config)
    {
        $class = $this->getMetaReflectionClass($meta);

        // property annotations
        foreach ($class->getProperties() as $property) {
            if ($writeProtected = $this->reader->getPropertyAnnotation($property, self::WRITE_PROTECTED)) {
                $config['fields'][] = $property->getName();
            }
        }
    }
}