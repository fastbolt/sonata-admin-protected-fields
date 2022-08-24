<?php

namespace Fastbolt\SonataAdminProtectedFields\Protection\Checker;

interface Checker
{
    public function getName(): string;

    public function shouldBeProtected($item): bool;
}
