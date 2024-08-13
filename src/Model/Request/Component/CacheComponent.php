<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Model\Request\Component;

use Psr\Cache\CacheItemInterface;
use Sidus\ApiClientBundle\Contracts\Request\Component\CacheComponentInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @see CacheComponentInterface
 */
class CacheComponent implements CacheComponentInterface
{
    protected ?CacheItemInterface $cacheItem = null;

    public function __construct(
        protected int $ttl,
        protected array $tags = [],
        protected ?string $user = null,
    ) {
    }

    public function getTtl(): int
    {
        return $this->ttl;
    }

    public function setTtl(int $ttl): self
    {
        $this->ttl = $ttl;

        return $this;
    }

    public function getTags(): array
    {
        return array_keys($this->tags);
    }

    public function setTags(array $tags): self
    {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }

        return $this;
    }

    public function addTag(string $tag): self
    {
        $this->tags[$tag] = true;

        return $this;
    }

    public function removeTag(string $tag): self
    {
        unset($this->tags[$tag]);

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(?string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCacheItem(): ?ItemInterface
    {
        return $this->cacheItem;
    }

    public function setCacheItem(?ItemInterface $cacheItem): self
    {
        $this->cacheItem = $cacheItem;

        return $this;
    }
}
