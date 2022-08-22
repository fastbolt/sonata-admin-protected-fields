<?php

/*
 * This file is part of the Doctrine Behavioral Extensions package.
 * (c) Gediminas Morkevicius <gediminas.morkevicius@gmail.com> http://www.gediminasm.org
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Fastbolt\SonataAdminProtectedFields\Mapping\Driver\Annotation;
use FOS\CKEditorBundle\DependencyInjection\FOSCKEditorExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SonataAdminProtectedFieldsBundle extends Bundle
{
    public function boot()
    {
        self::registerAnnotations();

        parent::boot();
    }

    private static function registerAnnotations()
    {
        /** @psalm-suppress DeprecatedMethod */
        AnnotationRegistry::registerFile(__DIR__ . '/Mapping/Annotations/DeleteProtected.php');
        AnnotationRegistry::registerFile(__DIR__ . '/Mapping/Annotations/WriteProtected.php');
    }
}
