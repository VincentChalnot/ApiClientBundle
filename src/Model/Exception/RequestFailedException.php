<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Model\Exception;

use Sidus\ApiClientBundle\Contracts\Response\ApiResponseInterface;

/**
 * This is a general exception thrown when a request failed
 */
class RequestFailedException extends \RuntimeException
{
    protected ?ApiResponseInterface $apiResponse = null;

    public static function createRequestFailedException(
        ApiResponseInterface $apiResponse,
        $message = 'Request have failed',
        ?\Throwable $previous = null,
    ): static {
        $message .= " (status code {$apiResponse->getStatusCode()})";

        $error = new static($message, 0, $previous);
        $error->apiResponse = $apiResponse;

        return $error;
    }

    public static function createFromResponse(ApiResponseInterface $apiResponse): static
    {
        $statusCode = $apiResponse->getStatusCode();
        if ($statusCode >= 400 && $statusCode < 500) {
            return ClientRequestFailedException::createClientRequestFailedException($apiResponse);
        }
        if ($statusCode >= 500 && $statusCode < 600) {
            return ServerRequestFailedException::createServerRequestFailedException($apiResponse);
        }
        return static::createRequestFailedException($apiResponse);
    }

    public function getApiResponse(): ?ApiResponseInterface
    {
        return $this->apiResponse;
    }
}
