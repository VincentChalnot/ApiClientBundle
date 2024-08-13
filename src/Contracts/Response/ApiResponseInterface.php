<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Contracts\Response;

use Sidus\ApiClientBundle\Contracts\Request\ApiRequestInterface;

interface ApiResponseInterface
{
    public function getApiRequest(): ApiRequestInterface;

    /**
     * Contains the raw body response
     */
    public function getBody(): string;

    public function getStatusCode(): ?int;

    public function getHeaders(): array;

    /**
     * Contains the parsed content of the response
     */
    public function getContent(): mixed;

    public function setContent(mixed $content): void;
}
