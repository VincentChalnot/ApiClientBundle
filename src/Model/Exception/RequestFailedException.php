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

class RequestFailedException extends \RuntimeException
{
    public function __construct(
        protected ApiResponseInterface $apiResponse,
        $message = 'Request have failed',
    ) {
        $message .= " (status code {$apiResponse->getStatusCode()})";

        parent::__construct($message);
    }

    public function getApiResponse(): ApiResponseInterface
    {
        return $this->apiResponse;
    }

    public static function createFromResponse(ApiResponseInterface $apiResponse): self
    {
        $statusCode = $apiResponse->getStatusCode();
        if ($statusCode >= 400 && $statusCode < 500) {
            return new ClientRequestFailedException($apiResponse);
        }
        if ($statusCode >= 500 && $statusCode < 600) {
            return new ServerRequestFailedException($apiResponse);
        }
        return new RequestFailedException($apiResponse);
    }
}
