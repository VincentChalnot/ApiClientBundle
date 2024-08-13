<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Model\Exception;

/**
 * Thrown when not able to deserialize a response from the API.
 */
class ApiDeserializationException extends \RuntimeException
{
    protected ?string $responseBody;

    protected string $className;

    public static function create(
        \Throwable $previous,
        ?string $responseBody,
        string $className,
    ): self {
        $exception = new self("Unable to deserialize data from response: {$responseBody}", 0, $previous);
        $exception->responseBody = $responseBody;
        $exception->className = $className;

        return $exception;
    }

    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }

    public function getClassName(): string
    {
        return $this->className;
    }
}
