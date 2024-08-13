<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Model\Authorization;

use Sidus\ApiClientBundle\Contracts\Request\ApiRequestInterface;
use Sidus\ApiClientBundle\Contracts\Request\Component\AuthorizationComponentInterface;

/**
 * Simple OAuth token implementation
 */
class OAuthToken implements AuthorizationComponentInterface
{
    public function __construct(
        protected string $accessToken,
        protected string $tokenType = 'Bearer',
        protected ?int $expiresIn = null,
        protected ?string $scope = null,
        protected ?string $jti = null,
    ) {
    }

    public function addAuthorization(ApiRequestInterface $apiRequest): void
    {
        $apiRequest->getHttpComponent()->addHeader(
            'Authorization',
            "{$this->getTokenType()} {$this->getAccessToken()}",
        );
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getExpiresIn(): ?int
    {
        return $this->expiresIn;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }

    public function getJti(): ?string
    {
        return $this->jti;
    }
}
