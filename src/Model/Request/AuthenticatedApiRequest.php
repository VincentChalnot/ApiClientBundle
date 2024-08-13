<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Model\Request;

use Sidus\ApiClientBundle\Contracts\Request\ApiRequestInterface;
use Sidus\ApiClientBundle\Contracts\Request\Component\AuthorizationComponentInterface;
use Sidus\ApiClientBundle\Contracts\Request\WithAuthorizationComponentInterface;

/**
 * @see ApiRequestInterface
 */
class AuthenticatedApiRequest extends ApiRequest implements WithAuthorizationComponentInterface
{
    protected ?AuthorizationComponentInterface $authorizationComponent = null;

    public function getAuthorizationComponent(): ?AuthorizationComponentInterface
    {
        return $this->authorizationComponent;
    }

    public function setAuthorizationComponent(?AuthorizationComponentInterface $authorizationComponent): self
    {
        $this->authorizationComponent = $authorizationComponent;

        return $this;
    }
}
