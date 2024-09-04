<?php
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Model\Exception;

use Sidus\ApiClientBundle\Contracts\Request\ApiRequestInterface;
use Sidus\ApiClientBundle\Contracts\Response\ApiResponseInterface;

/**
 * Global class for credential negotiation exceptions, including invalid credentials, bad token format, etc.
 */
class CredentialNegotiationException extends \RuntimeException
{
    protected ?ApiRequestInterface $apiRequest = null;

    protected ?ApiResponseInterface $apiResponse = null;

    public function getApiRequest(): ?ApiRequestInterface
    {
        return $this->apiRequest;
    }

    public function getApiResponse(): ?ApiResponseInterface
    {
        return $this->apiResponse;
    }
}
