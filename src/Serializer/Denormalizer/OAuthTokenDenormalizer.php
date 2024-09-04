<?php
/*
 * This file is part of the Sidus/ApiClientBundle package.
 * Copyright (C) 2017-2024 Vincent Chalnot
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sidus\ApiClientBundle\Serializer\Denormalizer;

use Sidus\ApiClientBundle\Model\Exception\MissingResponseParameterException;
use Sidus\ApiClientBundle\Model\Authorization\OAuthToken;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Denormalize authorization token from an API response, this is a very basic implementation
 * You might want to override this class to handle more complex responses
 */
class OAuthTokenDenormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): OAuthToken
    {
        if (!array_key_exists('access_token', $data)) {
            throw MissingResponseParameterException::createMissingResponseParameterException($data, 'access_token');
        }

        return new OAuthToken(
            $data['access_token'],
            $data['token_type'] ?? 'Bearer',
            $data['expires_in'] ?? null,
            $data['scope'] ?? null,
            $data['jti'] ?? null
        );
    }

    public function supportsDenormalization(
        mixed $data,
        string $type,
        ?string $format = null,
        array $context = []
    ): bool {
        return $type === OAuthToken::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [OAuthToken::class => true];
    }
}
