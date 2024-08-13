<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Contracts\Request\Component;

interface DeserializationComponentInterface
{
    /**
     * @return class-string
     */
    public function getClassName(): string;

    /**
     * @param class-string $className
     */
    public function setClassName(string $className): self;

    public function getDeserializationFormat(): ?string;

    public function setDeserializationFormat(?string $deserializationFormat): self;

    public function getDeserializationContext(): array;

    public function setDeserializationContext(array $deserializationContext): self;

    public function addDeserializationContext(string $key, $value): self;

    public function removeDeserializationContext(string $key): self;

    /**
     * @return class-string|null
     */
    public function getErrorClassName(): ?string;

    /**
     * Must be a throwable class
     *
     * @param class-string|null $errorClassName
    */
    public function setErrorClassName(?string $errorClassName): self;
}
