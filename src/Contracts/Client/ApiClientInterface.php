<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Contracts\Client;

use Sidus\ApiClientBundle\Contracts\Request\ApiRequestInterface;
use Sidus\ApiClientBundle\Contracts\Response\ApiResponseInterface;

/**
 * Base logic used to query a remote API object.
 */
interface ApiClientInterface
{
    public function query(ApiRequestInterface $apiRequest): ApiResponseInterface;
}
