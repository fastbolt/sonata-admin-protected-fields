<?php

namespace Fastbolt\SonataAdminProtectedFields\Protector;

use EntityProtectionInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Security\Handler\RoleSecurityHandler;
use Sonata\AdminBundle\Security\Handler\SecurityHandlerInterface;

class ProtectingSecurityHandler implements SecurityHandlerInterface
{

    private $secureAttributes = [
        'DELETE',
    ];

    private RoleSecurityHandler $parent;

    /**
     * @param RoleSecurityHandler $parent
     */
    public function __construct(RoleSecurityHandler $parent)
    {
        $this->parent = $parent;
    }

    public function isGranted(AdminInterface $admin, $attributes, ?object $object = null): bool
    {
        if (!$this->parent->isGranted($admin, $attributes, $object)) {
            return false;
        }

        if (null === $object || !is_object($object) || !$object instanceof EntityProtectionInterface) {
            return $this->parent->isGranted($admin, $attributes, $object);
        }

        if (!is_string($attributes)) {
            dd(func_get_args());
        }

        if (!in_array($attributes, $this->secureAttributes, true)) {
            return $this->parent->isGranted($admin, $attributes, $object);
        }

        if ($attributes === 'DELETE') {
            return !$object->isProtected();
        }

        dd(func_get_args());
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
