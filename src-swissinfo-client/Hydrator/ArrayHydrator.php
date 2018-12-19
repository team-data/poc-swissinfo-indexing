<?php

declare(strict_types=1);

namespace Liip\SwissinfoClient\Hydrator;

use Liip\SwissinfoClient\Exception\HydrationException;
use Psr\Http\Message\ResponseInterface;

/**
 * Hydrate an HTTP response to array.
 */
final class ArrayHydrator implements Hydrator
{
    /**
     * {@inheritdoc}
     */
    public function hydrate(ResponseInterface $response, string $class): array
    {
        $body = $response->getBody()->__toString();
        if (0 !== mb_strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
            throw new HydrationException('The ArrayHydrator cannot hydrate response with Content-Type:'.$response->getHeaderLine('Content-Type'));
        }

        $content = json_decode($body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new HydrationException(sprintf('Error (%d) when trying to json_decode response', json_last_error()));
        }

        return $content;
    }
}
