<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields\Protection\Checker;

use Fastbolt\SonataAdminProtectedFields\Exception\MethodNotFoundException;

/**
 * @Todo We might want to make this thing more configurable at a later time.
 *       The method {@link PropertyProtectionChecker::shouldBeProtected} should then receive
 *       the Attribute configuration as parameter.
 */
class PropertyProtectionChecker implements Checker
{
    public const NAME = 'PropertyProtectionChecker';

    public function shouldBeProtected(object $item): bool
    {
        if (!method_exists($item, 'isProtected')) {
            throw MethodNotFoundException::create('isProtected', get_class($item));
        }

        return $item->isProtected() === true;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
