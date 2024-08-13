<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Model\Exception;

class MissingCredentialsException extends \RuntimeException
{
    public function __construct(string $baseUri, $message = 'No credentials found matching the base URI: %s')
    {
        parent::__construct(sprintf($message, $baseUri));
    }
}
