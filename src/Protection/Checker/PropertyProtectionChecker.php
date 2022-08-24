<?php

namespace Fastbolt\SonataAdminProtectedFields\Protection\Checker;

/**
 * @Todo We might want to make this thing more configurable at a later time.
 *       The method {@link PropertyProtectionChecker::shouldBeProtected} should then receive
 *       the Attribute configuration as parameter.
 */
class PropertyProtectionChecker implements Checker
{
    public const NAME = 'PropertyProtectionChecker';

    public function shouldBeProtected($item): bool
    {
        return $item->isProtected();
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
