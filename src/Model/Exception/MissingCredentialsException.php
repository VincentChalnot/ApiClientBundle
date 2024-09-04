<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Model\Exception;

use Sidus\ApiClientBundle\Contracts\Request\ApiRequestInterface;

class MissingCredentialsException extends CredentialNegotiationException
{
    public static function createMissingCredentialsException(
        ApiRequestInterface $apiRequest,
        $message = 'No credentials found matching the base URI: %s',
    ): static {
        $error = new static(sprintf($message, $apiRequest->getHttpComponent()->getBaseUri()));
        $error->apiRequest = $apiRequest;

        return $error;
    }
}
