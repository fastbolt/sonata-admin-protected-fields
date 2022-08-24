<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields\DependencyInjection;

use Fastbolt\SonataAdminProtectedFields\Protection\DefaultProtector;
use Fastbolt\SonataAdminProtectedFields\Protection\EntitySecurityHandler\ProtectingSecurityHandler;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SonataAdminProtectedFieldsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');

        self::configureCheckers($container);
        self::configureSecurityHandler($container);
    }

    private static function configureCheckers(ContainerBuilder $container): void
    {
        $checkerIds = $container->findTaggedServiceIds('sonata_admin_protected_fields.protection_checkers');
        $checkers   = [];
        foreach (array_keys($checkerIds) as $checkerId) {
            $checkers[] = $container->findDefinition($checkerId);
        }

        $definition = $container->getDefinition(DefaultProtector::class);
        $definition->setArgument('$checkers', $checkers);

        $definition = $container->getDefinition(ProtectingSecurityHandler::class);
        $definition->setArgument('$checkers', $checkers);
    }

    private static function configureSecurityHandler(ContainerBuilder $container): void
    {
        /**
         * @var array<string,class-string|null> $configuration
         * @psalm-suppress UndefinedDocblockClass
         */
        $configuration                     = $container->getParameter(
            'sonata.admin.configuration.default_admin_services'
        ) ?? [];
        $configuration['security_handler'] = ProtectingSecurityHandler::class;

        $container->setParameter('sonata.admin.configuration.default_admin_services', $configuration);
    }
}
