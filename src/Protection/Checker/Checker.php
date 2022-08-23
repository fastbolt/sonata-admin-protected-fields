<?php

namespace Fastbolt\SonataAdminProtectedFields\Protection\Checker;

interface Checker
{
    public function shouldBeProtected($item);
}
