<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Sidus\ApiClientBundle\ApiClient;
use Sidus\ApiClientBundle\Contracts\Client\ApiClientInterface;
use Sidus\ApiClientBundle\Contracts\Client\CachedApiClientInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) {
    $containerConfigurator->services()
        ->set(ApiClient::class)
        ->private()
        ->autowire();

    $containerConfigurator->services()
        ->alias(ApiClientInterface::class, ApiClient::class);

    $containerConfigurator->services()
        ->alias(CachedApiClientInterface::class, ApiClient::class);

    // Register all event subscribers
    $containerConfigurator->services()
        ->load('Sidus\\ApiClientBundle\\Event\\', '../../Event/*')
        ->private()
        ->autowire()
        ->autoconfigure();

    // Register all serializers
    $containerConfigurator->services()
        ->load('Sidus\\ApiClientBundle\\Serializer\\', '../../Serializer/*')
        ->private()
        ->autowire()
        ->autoconfigure();
};
