<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Contracts\Request\Component;

use Sidus\ApiClientBundle\Contracts\Request\ApiRequestInterface;

interface AuthorizationComponentInterface
{
    public function addAuthorization(ApiRequestInterface $apiRequest): void;
}
