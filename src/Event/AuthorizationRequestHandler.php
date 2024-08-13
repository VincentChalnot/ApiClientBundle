<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Event;

use Sidus\ApiClientBundle\Contracts\Request\WithAuthorizationComponentInterface;
use Sidus\ApiClientBundle\Model\Event\ApiRequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuthorizationRequestHandler implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ApiRequestEvent::class => ['onApiRequest', -1000],
        ];
    }

    public function onApiRequest(ApiRequestEvent $event): void
    {
        $apiRequest = $event->request;
        if (!$apiRequest instanceof WithAuthorizationComponentInterface) {
            return;
        }
        $authorization = $apiRequest->getAuthorizationComponent();
        if (null === $authorization) {
            return;
        }

        $authorization->addAuthorization($apiRequest);
    }
}
