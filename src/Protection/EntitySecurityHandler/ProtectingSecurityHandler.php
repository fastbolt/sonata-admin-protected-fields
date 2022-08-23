<?php

namespace Fastbolt\SonataAdminProtectedFields\Protection\EntitySecurityHandler;

use Fastbolt\SonataAdminProtectedFields\Exception\CheckerNotFoundException;
use Fastbolt\SonataAdminProtectedFields\Mapping\Driver\AttributeDriver;
use Fastbolt\SonataAdminProtectedFields\Protection\Checker\Checker;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Security\Handler\RoleSecurityHandler;
use Sonata\AdminBundle\Security\Handler\SecurityHandlerInterface;

class ProtectingSecurityHandler implements SecurityHandlerInterface
{

    private RoleSecurityHandler $parent;

    private AttributeDriver $driver;

    /**
     * @var array<class-string,Checker>
     */
    private array $checkers = [];

    /**
     * @param array<class-string,Checker> $checkers
     */
    public function __construct(
        RoleSecurityHandler $parent,
        AttributeDriver $driver,
        array $checkers
    ) {
        $this->parent = $parent;
        $this->driver = $driver;

        foreach ($checkers as $checker) {
            $this->checkers[$checker::class] = $checker;
        }
    }

    public function isGranted(AdminInterface $admin, $attributes, ?object $object = null): bool
    {
        if (!$this->parent->isGranted($admin, $attributes, $object)) {
            return false;
        }

        if (!is_object($object) || $attributes !== 'DELETE') {
            return $this->parent->isGranted($admin, $attributes, $object);
        }

        if (null === ($deleteProtected = $this->driver->getDeleteProtection($object::class))) {
            return $this->parent->isGranted($admin, $attributes, $object);
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
