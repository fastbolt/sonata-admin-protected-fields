<?php

/*
 * This file is part of the Doctrine Behavioral Extensions package.
 * (c) Gediminas Morkevicius <gediminas.morkevicius@gmail.com> http://www.gediminasm.org
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields;

use Annotations\Driver\Annotation;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\PsrCachedReader;
use Doctrine\Common\Annotations\Reader;
use Sonata\Exporter\Bridge\Symfony\DependencyInjection\Compiler\ExporterCompilerPass;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SonataAdminProtectedFieldsBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
    }

    public static function registerMappingIntoDriverChainORM(
        MappingDriverChain $driverChain,
        Reader $reader = null
    ): void {
        AnnotationRegistry::registerFile(__DIR__ . '/Annotations/All.php');
        if (!$reader) {
            $reader = self::createAnnotationReader();
        }
        $annotationDriver = new Annotation($reader, [
            __DIR__ . '/Translatable/Entity',
            __DIR__ . '/Loggable/Entity',
            __DIR__ . '/Tree/Entity',
        ]);
        $driverChain->addDriver($annotationDriver, 'Gedmo');
    }

    private static function createAnnotationReader(): PsrCachedReader
    {
        return new PsrCachedReader(new AnnotationReader(), new ArrayAdapter());
    }
}
