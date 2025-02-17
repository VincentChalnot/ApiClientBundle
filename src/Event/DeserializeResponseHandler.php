<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Event;

use Sidus\ApiClientBundle\Contracts\Request\WithDeserializationComponentInterface;
use Sidus\ApiClientBundle\Contracts\Response\ApiResponseInterface;
use Sidus\ApiClientBundle\Model\Event\ApiResponseEvent;
use Sidus\ApiClientBundle\Model\Exception\DeserializationException;
use Sidus\ApiClientBundle\Model\Exception\ServerRequestFailedException;
use Sidus\ApiClientBundle\Model\Request\Component\DeserializationComponent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Serializer\SerializerInterface;

class DeserializeResponseHandler implements EventSubscriberInterface
{
    public function __construct(
        protected SerializerInterface $serializer,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApiResponseEvent::class => 'onApiResponse',
        ];
    }

    public function onApiResponse(ApiResponseEvent $event): void
    {
        $apiResponse = $event->response;
        if (null !== $apiResponse->getContent()) {
            return; // Response was already processed, don't interfere
        }
        $apiRequest = $apiResponse->getApiRequest();
        if (!$apiRequest instanceof WithDeserializationComponentInterface) {
            return;
        }
        $deserialization = $apiRequest->getDeserializationComponent();
        if (!$deserialization) {
            return;
        }

        try {
            $content = $this->serializer->deserialize(
                data: $apiResponse->getBody(),
                type: $deserialization->getClassName(),
                format: $deserialization->getDeserializationFormat(),
                context: $deserialization->getDeserializationContext()
            );
        } catch (\Exception $exception) {
            $this->handleDeserializationException($apiResponse, $deserialization, $exception);
        }

        $apiResponse->setContent($content);
    }

    /**
     * @todo test the use of the errorClassName option
     */
    protected function handleDeserializationException(
        ApiResponseInterface $apiResponse,
        DeserializationComponent $deserialization,
        \Exception $exception,
    ): void {
        $errorClassName = $deserialization->getErrorClassName();
        if (!$errorClassName) {
            throw DeserializationException::createApiDeserializationException(
                apiResponse: $apiResponse,
                previous: $exception,
                className: $deserialization->getClassName(),
            );
        }

        try {
            $apiException = $this->serializer->deserialize(
                data: $apiResponse->getBody(),
                type: $errorClassName,
                format: $deserialization->getDeserializationFormat(),
                context: $deserialization->getDeserializationContext()
            );
        } catch (\Exception) {
            throw DeserializationException::createApiDeserializationException(
                apiResponse: $apiResponse,
                previous: $exception,
                className: $deserialization->getClassName(),
            );
        }
        if (!$apiException instanceof \Throwable) {
            throw new \LogicException("Error class {$errorClassName} is not a valid Throwable");
        }
        throw new ServerRequestFailedException(
            "The API returned an error: {$apiException->getMessage()}",
            0,
            $apiException,
        );
    }
}
