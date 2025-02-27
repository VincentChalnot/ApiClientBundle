<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Model\Event;

use Sidus\ApiClientBundle\Contracts\Response\ApiResponseInterface;
use Symfony\Contracts\EventDispatcher\Event;

class ApiResponseEvent extends Event
{
    public function __construct(
        public ApiResponseInterface $response,
    ) {
    }
}
