<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Model\Request;

use Sidus\ApiClientBundle\Contracts\Request\ApiRequestInterface;
use Sidus\ApiClientBundle\Contracts\Request\Component\CacheComponentInterface;
use Sidus\ApiClientBundle\Contracts\Request\Component\DeserializationComponentInterface;
use Sidus\ApiClientBundle\Contracts\Request\Component\HttpComponentInterface;
use Sidus\ApiClientBundle\Contracts\Request\Component\SerializationComponentInterface;
use Sidus\ApiClientBundle\Contracts\Request\WithCacheComponentInterface;
use Sidus\ApiClientBundle\Contracts\Request\WithDeserializationComponentInterface;
use Sidus\ApiClientBundle\Contracts\Request\WithSerializationComponentInterface;

class ApiRequest implements ApiRequestInterface, WithCacheComponentInterface, WithSerializationComponentInterface, WithDeserializationComponentInterface
{
    protected ?CacheComponentInterface $cacheComponent = null;

    protected ?SerializationComponentInterface $serializationComponent = null;

    protected ?DeserializationComponentInterface $deserializationComponent = null;

    public function __construct(
        protected HttpComponentInterface $httpComponent,
    ) {
    }

    public function getHttpComponent(): HttpComponentInterface
    {
        return $this->httpComponent;
    }

    public function setHttpComponent(HttpComponentInterface $httpComponent): self
    {
        $this->httpComponent = $httpComponent;

        return $this;
    }

    public function getCacheComponent(): ?CacheComponentInterface
    {
        return $this->cacheComponent;
    }

    public function setCacheComponent(?CacheComponentInterface $cacheComponent): self
    {
        $this->cacheComponent = $cacheComponent;

        return $this;
    }

    public function getSerializationComponent(): ?SerializationComponentInterface
    {
        return $this->serializationComponent;
    }

    public function setSerializationComponent(?SerializationComponentInterface $serializationComponent): self
    {
        $this->serializationComponent = $serializationComponent;

        return $this;
    }

    public function getDeserializationComponent(): ?DeserializationComponentInterface
    {
        return $this->deserializationComponent;
    }

    public function setDeserializationComponent(?DeserializationComponentInterface $deserializationComponent): self
    {
        $this->deserializationComponent = $deserializationComponent;

        return $this;
    }
}
