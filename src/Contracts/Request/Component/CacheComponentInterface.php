<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Contracts\Request\Component;

use Symfony\Contracts\Cache\ItemInterface;

/**
 * Adding cache settings to standard API request.
 */
interface CacheComponentInterface
{
    public function getTtl(): int;

    public function setTtl(int $ttl): self;

    public function getTags(): array;

    public function setTags(array $tags): self;

    public function addTag(string $tag): self;

    public function removeTag(string $tag): self;

    /**
     * User identifier to differentiate cache between users and allow private cache.
     */
    public function getUser(): ?string;

    public function setUser(?string $user): self;

    /**
     * Temporary cache item to pass data between request and response.
     */
    public function getCacheItem(): ?ItemInterface;

    public function setCacheItem(?ItemInterface $cacheItem): self;
}
