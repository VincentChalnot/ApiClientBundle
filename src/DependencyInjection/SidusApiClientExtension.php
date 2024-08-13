<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\DependencyInjection;

use Sidus\ApiClientBundle\Contracts\Authorization\CredentialsInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class SidusApiClientExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader(
            container: $container,
            locator: new FileLocator(__DIR__.'/../Resources/config'),
        );
        $loader->load('services.php');

        $container->registerForAutoconfiguration(CredentialsInterface::class)
            ->addTag('sidus.api_client.credentials');
    }
}
