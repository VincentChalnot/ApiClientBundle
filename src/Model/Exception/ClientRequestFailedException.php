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
 * Thrown when the request failed on the client side
 */
class ClientRequestFailedException extends RequestFailedException
{
    public static function createClientRequestFailedException(
        ApiResponseInterface $apiResponse,
        $message = 'Request have failed on client side',
    ): static {
        $statusCode = $apiResponse->getStatusCode();
        if ($statusCode < 400 || $statusCode >= 500) {
            throw new \InvalidArgumentException('Exception reserved to 4xx responses');
        }

        return static::createRequestFailedException($apiResponse, $message);
    }
}
