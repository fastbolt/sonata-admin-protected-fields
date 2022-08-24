<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields\Protection\EntitySecurityHandler;

use Fastbolt\SonataAdminProtectedFields\Exception\CheckerNotFoundException;
use Fastbolt\SonataAdminProtectedFields\Mapping\Driver\AttributeDriver;
use Fastbolt\SonataAdminProtectedFields\Protection\Checker\Checker;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Security\Handler\SecurityHandlerInterface;

class ProtectingSecurityHandler implements SecurityHandlerInterface
{
    private SecurityHandlerInterface $parent;

    private AttributeDriver $driver;

    /**
     * @var array<string,Checker>
     */
    private array $checkers = [];

    /**
     * @param array<string,Checker> $checkers
     */
    public function __construct(
        SecurityHandlerInterface $parent,
        AttributeDriver $driver,
        array $checkers
    ) {
        $this->parent = $parent;
        $this->driver = $driver;

        foreach ($checkers as $checker) {
            $this->checkers[$checker->getName()] = $checker;
        }
    }

    public function isGranted(AdminInterface $admin, $attributes, ?object $object = null): bool
    {
        if (false === ($parentGranted = $this->parent->isGranted($admin, $attributes, $object))) {
            return false;
        }

        if (!is_object($object) || $attributes !== 'DELETE') {
            return $parentGranted;
        }

        if (null === ($deleteProtected = $this->driver->getDeleteProtection($object::class))) {
            return $parentGranted;
        }

        $checkerName = $deleteProtected->getChecker();
        if (null === ($checker = $this->checkers[$checkerName] ?? null)) {
            throw CheckerNotFoundException::create($checkerName);
        }

        if (true === $checker->shouldBeProtected($object)) {
            return false;
        }

        return true;
    }

    public function getBaseRole(AdminInterface $admin): string
    {
        return $this->parent->getBaseRole($admin);
    }

    public function buildSecurityInformation(AdminInterface $admin): array
    {
        return $this->parent->buildSecurityInformation($admin);
    }

    public function createObjectSecurity(AdminInterface $admin, object $object): void
    {
        $this->parent->createObjectSecurity($admin, $object);
    }

    public function deleteObjectSecurity(AdminInterface $admin, object $object): void
    {
        $this->parent->deleteObjectSecurity($admin, $object);
    }
}
