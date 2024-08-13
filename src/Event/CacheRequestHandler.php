<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Event;

use Psr\Log\LoggerInterface;
use Sidus\ApiClientBundle\Contracts\Request\ApiRequestInterface;
use Sidus\ApiClientBundle\Contracts\Request\Component\CacheComponentInterface;
use Sidus\ApiClientBundle\Contracts\Request\WithCacheComponentInterface;
use Sidus\ApiClientBundle\Model\Event\ApiRequestEvent;
use Sidus\ApiClientBundle\Model\Event\ApiResponseEvent;
use Sidus\ApiClientBundle\Model\Event\InvalidateCacheEvent;
use Sidus\ApiClientBundle\Model\Response\ApiResponse;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CacheRequestHandler implements EventSubscriberInterface
{
    public function __construct(
        protected LoggerInterface $logger,
        protected ?TagAwareAdapterInterface $cache = null,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApiRequestEvent::class => ['onApiRequest', -10000],
            ApiResponseEvent::class => ['onApiResponse', 10000],
            InvalidateCacheEvent::class => 'onInvalidateCache',
        ];
    }

    public function onApiRequest(ApiRequestEvent $event): void
    {
        $apiRequest = $event->request;
        if (!$apiRequest instanceof WithCacheComponentInterface) {
            return;
        }
        $cache = $apiRequest->getCacheComponent();
        if (null === $cache) {
            return;
        }
        if (!$this->cache) {
            $this->logger->warning('CacheRequestHandler is enabled but no cache provider is available');

            return;
        }

        $cacheKey = $this->getCacheKey($apiRequest, $cache);

        $cacheItem = $this->cache->getItem($cacheKey);
        $cache->setCacheItem($cacheItem);

        if (!$cacheItem->isHit()) {
            return; // Cache miss
        }

        // Cache hit, set response.
        $event->response = new ApiResponse($apiRequest, $cacheItem->get(), 200);
    }

    public function onApiResponse(ApiResponseEvent $event): void
    {
        $apiResponse = $event->response;
        $apiRequest = $apiResponse->getApiRequest();
        if (!$apiRequest instanceof WithCacheComponentInterface) {
            return;
        }
        $cache = $apiRequest->getCacheComponent();
        if (null === $cache) {
            return;
        }
        if (!$this->cache) {
            return;
        }

        $cacheItem = $cache->getCacheItem();
        if (!$cacheItem) {
            return; // Throw exception?
        }
        if ($cacheItem->isHit()) {
            return; // Previous cache hit, no need to store again
        }

        $cacheItem->set($apiResponse->getBody());
        $cacheItem->expiresAfter($cache->getTtl());
        $cacheItem->tag($this->getTags($cache->getTags(), $cache->getUser()));
        $this->cache->save($cacheItem);
    }

    public function onInvalidateCache(InvalidateCacheEvent $event): void
    {
        if (!$this->cache) {
            return;
        }
        $this->cache->invalidateTags($this->getTags($event->tags, $event->user));
    }

    protected function getTags(array $tags, ?string $user): array
    {
        if (!$user) {
            return $tags;
        }
        $subCacheKey = sha1($user);
        $privateTags = [];
        foreach ($tags as $tag) {
            $privateTags[] = $tag.'_'.$subCacheKey;
        }

        return $privateTags;
    }

    protected function getCacheKey(ApiRequestInterface $apiRequest, CacheComponentInterface $cacheComponent): string
    {
        $http = $apiRequest->getHttpComponent();
        $subCacheKey = $http->getMethod().$http->getUri().$http->getBody();
        $user = $cacheComponent->getUser();
        if ($user) {
            // Append user identifier to cache key if private
            $subCacheKey .= '@'.$user;
        }

        return sha1($subCacheKey);
    }
}
