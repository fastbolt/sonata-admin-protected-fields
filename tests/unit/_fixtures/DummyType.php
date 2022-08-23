<?php

namespace Fastbolt\SonataAdminProtectedFields\Tests\Unit\_fixtures;

class DummyType
{

    public function __construct(private bool $isProtected)
    {
    }

    public function isProtected(): bool
    {
        return $this->isProtected;
    }
}