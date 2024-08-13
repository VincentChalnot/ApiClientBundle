<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Contracts\Request\Component;

interface HttpComponentInterface
{
    public function getBaseUri(): string;

    public function setBaseUri(string $baseUri): self;

    public function getPath(): string;

    public function setPath(string $path): self;

    public function getUri(): string;

    public function getMethod(): string;

    public function setMethod(string $method): self;

    public function getBody(): ?string;

    public function setBody(?string $body): self;

    public function getContentType(): string;

    public function setContentType(string $contentType): self;

    public function getHeaders(): array;

    public function setHeaders(array $headers): self;

    public function addHeader(string $name, string $value): self;

    public function removeHeader(string $name): self;
}
