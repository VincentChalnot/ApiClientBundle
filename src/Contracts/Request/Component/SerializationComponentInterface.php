<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Contracts\Request\Component;

interface SerializationComponentInterface
{
    public function getContent(): object|array;

    public function setContent(object|array $content): self;

    public function getSerializationFormat(): ?string;

    public function setSerializationFormat(?string $serializationFormat): self;

    public function getSerializationContext(): array;

    public function setSerializationContext(array $serializationContext): self;
}
