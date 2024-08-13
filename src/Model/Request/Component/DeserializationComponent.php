<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Model\Request\Component;

use Sidus\ApiClientBundle\Contracts\Request\Component\DeserializationComponentInterface;

class DeserializationComponent implements DeserializationComponentInterface
{
    public function __construct(
        protected string $className,
        protected ?string $deserializationFormat = 'json',
        protected array $deserializationContext = [],
        protected ?string $errorClassName = null,
    ) {
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function setClassName(string $className): DeserializationComponent
    {
        $this->className = $className;

        return $this;
    }

    public function getDeserializationFormat(): string
    {
        return $this->deserializationFormat;
    }

    public function setDeserializationFormat(?string $deserializationFormat): DeserializationComponent
    {
        $this->deserializationFormat = $deserializationFormat;

        return $this;
    }

    public function getDeserializationContext(): array
    {
        return $this->deserializationContext;
    }

    public function setDeserializationContext(array $deserializationContext): DeserializationComponent
    {
        $this->deserializationContext = $deserializationContext;

        return $this;
    }

    public function addDeserializationContext(string $key, $value): DeserializationComponent
    {
        $this->deserializationContext[$key] = $value;

        return $this;
    }

    public function removeDeserializationContext(string $key): DeserializationComponent
    {
        unset($this->deserializationContext[$key]);

        return $this;
    }

    public function getErrorClassName(): ?string
    {
        return $this->errorClassName;
    }

    public function setErrorClassName(?string $errorClassName): DeserializationComponent
    {
        if (!is_a($errorClassName, \Throwable::class, true)) {
            throw new \LogicException("Error class {$errorClassName} is not a valid Throwable");
        }
        $this->errorClassName = $errorClassName;

        return $this;
    }
}
