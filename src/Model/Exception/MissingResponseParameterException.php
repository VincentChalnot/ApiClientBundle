<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Model\Exception;

class MissingResponseParameterException extends \RuntimeException
{
    public static function create(array $data, string $parameter, ?string $message = null): self
    {
        $message ??= 'Available parameters are '.implode(', ', array_keys($data));

        return new self(
            "Missing parameter '{$parameter}' in response: {$message}",
        );
    }
}
