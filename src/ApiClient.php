<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Sidus\ApiClientBundle\Contracts\Client\ApiClientInterface;
use Sidus\ApiClientBundle\Contracts\Client\CachedApiClientInterface;
use Sidus\ApiClientBundle\Contracts\Request\ApiRequestInterface;
use Sidus\ApiClientBundle\Contracts\Response\ApiResponseInterface;
use Sidus\ApiClientBundle\Model\Event\ApiRequestEvent;
use Sidus\ApiClientBundle\Model\Event\ApiResponseEvent;
use Sidus\ApiClientBundle\Model\Event\InvalidateCacheEvent;
use Sidus\ApiClientBundle\Model\Response\ApiResponse;

/**
 * @see ApiClientInterface
 */
class ApiClient implements CachedApiClientInterface
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
        protected ClientInterface $client,
        protected RequestFactoryInterface $requestFactory,
        protected StreamFactoryInterface $streamFactory,
    ) {
    }

    public function query(ApiRequestInterface $apiRequest): ApiResponseInterface
    {
        $event = new ApiRequestEvent($apiRequest);
        $this->eventDispatcher->dispatch($event);

        $apiResponse = $event->response;
        if (null === $apiResponse) { // Response can be set by event listener in case of cache hit for example
            $httpRequest = $this->createHttpRequest($event->request);
            $httpResponse = $this->client->sendRequest($httpRequest);

            $apiResponse = new ApiResponse(
                $apiRequest,
                $httpResponse->getBody()->getContents(),
                $httpResponse->getStatusCode(),
                $httpResponse->getHeaders(),
            );
        }

        $event = new ApiResponseEvent($apiResponse);
        $this->eventDispatcher->dispatch($event);

        return $event->response;
    }

    public function invalidate(array $tags, ?string $user = null): void
    {
        $invalidateCacheEvent = new InvalidateCacheEvent($tags, $user);
        $this->eventDispatcher->dispatch($invalidateCacheEvent);
    }

    protected function createHttpRequest(ApiRequestInterface $apiRequest): RequestInterface
    {
        $http = $apiRequest->getHttpComponent();
        $httpRequest = $this->requestFactory->createRequest($http->getMethod(), $http->getUri());
        $body = $http->getBody();
        if ($body) {
            // Note: None of the ClientInterface implementation actually supports streamable bodies,
            // body will always be cast to a string in the end so no need to support streams here.
            $httpRequest = $httpRequest->withBody($this->streamFactory->createStream($body));
        }
        $httpRequest = $httpRequest->withHeader('Content-Type', $http->getContentType());
        foreach ($http->getHeaders() as $key => $value) {
            $httpRequest = $httpRequest->withHeader($key, $value);
        }

        return $httpRequest;
    }
}
