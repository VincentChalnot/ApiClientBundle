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

class ServerRequestFailedException extends RequestFailedException
{
    public static function createServerRequestFailedException(
        ApiResponseInterface $apiResponse,
        $message = 'Request have failed on server side',
    ): static {
        $statusCode = $apiResponse->getStatusCode();
        if ($statusCode < 500 || $statusCode >= 600) {
            throw new \InvalidArgumentException('Exception reserved to 5xx responses');
        }

        return static::createRequestFailedException($apiResponse, $message);
    }
}
