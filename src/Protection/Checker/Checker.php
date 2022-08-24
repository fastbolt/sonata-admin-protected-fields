<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields\Protection\Checker;

interface Checker
{
    public function getName(): string;

    public function shouldBeProtected($item): bool;
}
