<?php

namespace Fastbolt\SonataAdminProtectedFields\Mapping\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\PsrCachedReader;
use Fastbolt\SonataAdminProtectedFields\Mapping\Annotations\DeleteProtected;
use Fastbolt\SonataAdminProtectedFields\Mapping\Annotations\WriteProtected;
use ReflectionClass;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class AnnotationDriver
{
    /**
     * Annotation to identify field as translatable
     */
    public const WRITE_PROTECTED = WriteProtected::class;

    public const DELETE_PROTECTED = DeleteProtected::class;

    private bool $debug;

    /**
     * @param bool $debug
     */
    public function __construct(bool $debug)
    {
        $this->debug = $debug;
    }

    public function getProtectedFields(string $modelClass)
    {
        $reflectionClass = new ReflectionClass($modelClass);
        $properties      = $reflectionClass->getProperties();
        $reader          = $this->createAnnotationReader();
        $annotations     = [];
        foreach ($properties as $property) {
            $annotations[$property->getName()] = $reader->getPropertyAnnotation($property, self::WRITE_PROTECTED);
        }

        dd($annotations);

        $config['fields'][] = $property->getName();
    }

    private function createAnnotationReader(): PsrCachedReader
    {
        return new PsrCachedReader(new AnnotationReader(), new ArrayAdapter(), $this->debug);
    }
}
