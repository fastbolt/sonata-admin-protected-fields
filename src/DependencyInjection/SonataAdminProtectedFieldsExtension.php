<?php

namespace Fastbolt\SonataAdminProtectedFields\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SonataAdminProtectedFieldsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
//        $x = $container->getParameter('sonata.admin.configuration.default_admin_services');
//        $x['security_handler']= 'fooo';
//        $container->setParameter('sonata.admin.configuration.default_admin_services', false);
//
//
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');
    }
}
