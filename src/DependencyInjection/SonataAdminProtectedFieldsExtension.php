<?php

namespace Fastbolt\SonataAdminProtectedFields\DependencyInjection;

use Fastbolt\SonataAdminProtectedFields\Protection\DefaultProtector;
use Fastbolt\SonataAdminProtectedFields\Protection\EntitySecurityHandler\ProtectingSecurityHandler;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SonataAdminProtectedFieldsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');

        self::configureCheckers($container);
        self::configureSecurityHandler($container);
    }

    private static function configureCheckers(ContainerBuilder $container)
    {
        $checkerIds = $container->findTaggedServiceIds('sonata_admin_protected_fields.protection_checkers');
        $checkers   = [];
        foreach ($checkerIds as $checkerId => $tags) {
            $checkers[] = $container->findDefinition($checkerId);
        }

        $definition = $container->getDefinition(DefaultProtector::class);
        $definition->setArgument('$checkers', $checkers);

        $definition = $container->getDefinition(ProtectingSecurityHandler::class);
        $definition->setArgument('$checkers', $checkers);
    }

    private static function configureSecurityHandler(ContainerBuilder $container)
    {
        $configuration                     = $container->getParameter(
            'sonata.admin.configuration.default_admin_services'
        );
        $configuration['security_handler'] = ProtectingSecurityHandler::class;

        $container->setParameter('sonata.admin.configuration.default_admin_services', $configuration);
    }
}
