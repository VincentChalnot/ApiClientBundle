<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Model\Response;

use Sidus\ApiClientBundle\Contracts\Request\ApiRequestInterface;
use Sidus\ApiClientBundle\Contracts\Response\ApiResponseInterface;

class ApiResponse implements ApiResponseInterface
{
    protected mixed $content = null;

    protected ?\Exception $exception = null;

    public function __construct(
        protected ApiRequestInterface $apiRequest,
        protected string $response,
        protected ?int $statusCode = null,
        protected array $headers = [],
    ) {
    }

    public function getApiRequest(): ApiRequestInterface
    {
        return $this->apiRequest;
    }

    public function getBody(): string
    {
        return $this->response;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }


    public function getContent(): mixed
    {
        return $this->content;
    }

    public function setContent(mixed $content): void
    {
        $this->content = $content;
    }
}
