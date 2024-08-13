<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Event;

use Sidus\ApiClientBundle\Contracts\Request\WithSerializationComponentInterface;
use Sidus\ApiClientBundle\Model\Event\ApiRequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SerializeRequestHandler implements EventSubscriberInterface
{
    public function __construct(
        protected SerializerInterface $serializer,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApiRequestEvent::class => 'onApiRequest',
        ];
    }

    public function onApiRequest(ApiRequestEvent $event): void
    {
        $apiRequest = $event->request;
        $http = $apiRequest->getHttpComponent();
        if (null !== $http->getBody()) {
            return; // Request was already processed, don't interfere
        }
        if (!$apiRequest instanceof WithSerializationComponentInterface) {
            return;
        }
        $serialization = $apiRequest->getSerializationComponent();
        if (!$serialization) {
            return;
        }

        $body = $this->serializer->serialize(
            data: $serialization->getContent(),
            format: $serialization->getSerializationFormat(),
            context: $serialization->getSerializationContext(),
        );

        $http->setBody($body);
    }
}
