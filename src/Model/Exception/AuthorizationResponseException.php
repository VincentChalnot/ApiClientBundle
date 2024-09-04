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
 * Thrown when unable to authenticate against the server API.
 */
class AuthorizationResponseException extends CredentialNegotiationException
{
    public static function createAuthorizationResponseException(
        ApiResponseInterface $apiResponse,
    ): static {
        $error = new static(
            sprintf(
                'Unable to get authorization from remote server %s: %d',
                $apiResponse->getApiRequest()->getHttpComponent()->getUri(),
                $apiResponse->getStatusCode(),
            ),
        );
        $error->apiRequest = $apiResponse->getApiRequest();
        $error->apiResponse = $apiResponse;

        return $error;
    }
}
