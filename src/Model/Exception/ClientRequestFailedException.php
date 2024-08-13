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

class ClientRequestFailedException extends RequestFailedException
{
    public function __construct(ApiResponseInterface $apiResponse, $message = 'Request have failed on client side')
    {
        $statusCode = $apiResponse->getStatusCode();
        if ($statusCode < 400 || $statusCode >= 500) {
            throw new \InvalidArgumentException('Exception reserved to 4xx responses');
        }

        parent::__construct($apiResponse, $message);
    }
}
