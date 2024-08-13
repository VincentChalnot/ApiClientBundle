<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Event;

use Sidus\ApiClientBundle\Contracts\Authorization\CredentialsInterface;
use Sidus\ApiClientBundle\Contracts\Client\ApiClientInterface;
use Sidus\ApiClientBundle\Contracts\Request\Component\AuthorizationComponentInterface;
use Sidus\ApiClientBundle\Contracts\Request\WithAuthorizationComponentInterface;
use Sidus\ApiClientBundle\Contracts\Response\ApiResponseInterface;
use Sidus\ApiClientBundle\Model\Exception\MissingCredentialsException;
use Sidus\ApiClientBundle\Model\Exception\AuthorizationResponseException;
use Sidus\ApiClientBundle\Model\Event\ApiRequestEvent;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Handles authentication for API requests, looks for credentials matching the base URI, requests an
 * authentication token and attaches it to the request as an AuthorizationComponentInterface
 */
class RequestAuthorizationHandler implements EventSubscriberInterface
{
    /**
     * @param iterable<CredentialsInterface> $credentialsCollection
     */
    public function __construct(
        #[AutowireIterator('sidus.api_client.credentials')]
        protected iterable $credentialsCollection,
        protected ApiClientInterface $apiClient,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApiRequestEvent::class => 'onApiRequest',
        ];
    }

    public function onApiRequest(ApiRequestEvent $event): void
    {
        $apiRequest = $event->request;
        if (!$apiRequest instanceof WithAuthorizationComponentInterface) {
            return;
        }
        $authorization = $apiRequest->getAuthorizationComponent();
        if ($authorization) {
            return; // Already authorized somewhere else
        }

        $this->authorize($apiRequest);
    }

    protected function authorize(WithAuthorizationComponentInterface $apiRequest): void
    {
        $http = $apiRequest->getHttpComponent();
        $credentials = $this->getCredentialsForBaseUri($http->getBaseUri());
        if (null === $credentials) {
            throw new MissingCredentialsException($apiRequest->getHttpComponent()->getBaseUri());
        }

        $authorizationRequest = $credentials->createAuthorizationRequest();
        $authorizationResponse = $this->apiClient->query($authorizationRequest);
        $token = $this->handleAuthorizationResponse($authorizationResponse);

        $apiRequest->setAuthorizationComponent($token);
    }

    protected function getCredentialsForBaseUri(string $baseUri): ?CredentialsInterface
    {
        foreach ($this->credentialsCollection as $credentials) {
            if ($credentials->getBaseUri() === $baseUri) {
                return $credentials;
            }
        }

        return null;
    }

    protected function handleAuthorizationResponse(ApiResponseInterface $apiResponse): AuthorizationComponentInterface
    {
        if (200 !== $apiResponse->getStatusCode()) {
            throw new AuthorizationResponseException(
                $apiResponse,
                sprintf(
                    'Unable to get authorization from remote server %s: %d',
                    $apiResponse->getApiRequest()->getHttpComponent()->getUri(),
                    $apiResponse->getStatusCode(),
                ),
            );
        }

        $content = $apiResponse->getContent();
        if (!$content instanceof AuthorizationComponentInterface) {
            // This should never happen, this means that your configuration is wrong
            throw new AuthorizationResponseException(
                $apiResponse,
                sprintf(
                    'Authorization response from remote server %s is not an AuthorizationComponentInterface: %s',
                    $apiResponse->getApiRequest()->getHttpComponent()->getUri(),
                    get_debug_type($content),
                ),
            );
        }

        return $content;
    }
}
