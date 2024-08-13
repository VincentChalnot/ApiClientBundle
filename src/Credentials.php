<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle;

use Sidus\ApiClientBundle\Contracts\Authorization\CredentialsInterface;
use Sidus\ApiClientBundle\Contracts\Request\ApiRequestInterface;
use Sidus\ApiClientBundle\Model\Authorization\OAuthToken;
use Sidus\ApiClientBundle\Model\Request\ApiRequest;
use Sidus\ApiClientBundle\Model\Request\Component\CacheComponent;
use Sidus\ApiClientBundle\Model\Request\Component\DeserializationComponent;
use Sidus\ApiClientBundle\Model\Request\Component\HttpComponent;

/**
 * Declare a service using this class in your app to provide the credentials for the OAuth token request
 */
class Credentials implements CredentialsInterface
{
    public function __construct(
        protected string $baseUrl,
        protected string $path,
        protected array $authenticationParams,
        protected string $contentType = 'application/json',
        protected string $responseClass = OAuthToken::class,
        protected string $responseFormat = 'json',
    ) {
    }

    public function getBaseUri(): string
    {
        return $this->baseUrl;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getAuthenticationParams(): array
    {
        return $this->authenticationParams;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function getResponseClass(): string
    {
        return $this->responseClass;
    }

    public function getResponseFormat(): string
    {
        return $this->responseFormat;
    }

    public function createAuthorizationRequest(): ApiRequestInterface
    {
        if ('application/x-www-form-urlencoded' === $this->getContentType()) {
            $body = http_build_query($this->getAuthenticationParams());
        } elseif ('application/json' === $this->getContentType()) {
            $body = json_encode($this->getAuthenticationParams(), JSON_THROW_ON_ERROR);
        } else {
            $m = "Unsupported token request content type {$this->getContentType()}";
            throw new \UnexpectedValueException($m);
        }

        return (new ApiRequest(
            new HttpComponent(
                baseUri: $this->getBaseUri(),
                path: $this->getPath(),
                method: 'POST',
                body: $body,
                contentType: $this->getContentType(),
            ),
        ))->setCacheComponent(
            new CacheComponent(
                ttl: 3600,
                tags: ['sidus.api_client.authorization', 'sidus.api_client.authorization_'.sha1($this->getBaseUri())],
            ),
        )->setDeserializationComponent(
            new DeserializationComponent(
                className: $this->getResponseClass(),
                deserializationFormat: $this->getResponseFormat(),
            ),
        );
    }
}
