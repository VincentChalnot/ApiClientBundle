<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Model\Request\Component;

use Sidus\ApiClientBundle\Contracts\Request\Component\SerializationComponentInterface;

class SerializationComponent implements SerializationComponentInterface
{
    public function __construct(
        protected array|object $content,
        protected ?string $serializationFormat = 'json',
        protected array $serializationContext = [],
    ) {
    }

    public function getContent(): object|array
    {
        return $this->content;
    }

    public function setContent(object|array $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getSerializationFormat(): ?string
    {
        return $this->serializationFormat;
    }

    public function setSerializationFormat(?string $serializationFormat): self
    {
        $this->serializationFormat = $serializationFormat;

        return $this;
    }

    public function getSerializationContext(): array
    {
        return $this->serializationContext;
    }

    public function setSerializationContext(array $serializationContext): self
    {
        $this->serializationContext = $serializationContext;

        return $this;
    }
}
