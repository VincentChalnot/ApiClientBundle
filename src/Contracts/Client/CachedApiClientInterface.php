<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Contracts\Client;

/**
 * Additional method to manage cache for API requests.
 */
interface CachedApiClientInterface extends ApiClientInterface
{
    /**
     * Specify the user to invalidate private cache
     */
    public function invalidate(array $tags, ?string $user = null): void;
}
