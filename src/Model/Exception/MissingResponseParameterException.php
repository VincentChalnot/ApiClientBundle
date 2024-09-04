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
 * This exception is thrown when a token response is missing the expected parameter
 */
class MissingResponseParameterException extends CredentialNegotiationException
{
    public static function createMissingResponseParameterException(
        array $data,
        string $parameter,
        ?string $message = null,
    ): static {
        $message ??= 'Available parameters are '.implode(', ', array_keys($data));

        return new static(
            "Missing parameter '{$parameter}' in response: {$message}",
        );
    }
}
