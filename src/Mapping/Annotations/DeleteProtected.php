<?php

namespace Annotations;

use Annotations\Annotation;
use Attribute;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @NamedArgumentConstructor
 * @Target("CLASS")
 */
#[Attribute(Attribute::TARGET_CLASS)]
class DeleteProtected implements Annotation
{
}
