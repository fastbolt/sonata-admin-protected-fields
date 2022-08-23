<?php

namespace Fastbolt\SonataAdminProtectedFields\Protection\Checker;

class PropertyProtectionChecker implements Checker
{
    public function shouldBeProtected($item)
    {
        return $item->isProtected();
    }
}
