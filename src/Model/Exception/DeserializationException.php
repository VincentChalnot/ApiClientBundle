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
 * Thrown when not able to deserialize a response from the API.
 */
class DeserializationException extends ClientRequestFailedException
{
    protected string $className;

    public static function createApiDeserializationException(
        ApiResponseInterface $apiResponse,
        \Throwable $previous,
        string $className,
    ): static {
        $exception = new static("Unable to deserialize data from response: {$apiResponse->getBody()}", 0, $previous);
        $exception->apiResponse = $apiResponse;
        $exception->className = $className;

        return $exception;
    }


    public function getClassName(): string
    {
        return $this->className;
    }
}
