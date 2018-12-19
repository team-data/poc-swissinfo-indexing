<?php

declare(strict_types=1);

namespace Liip\SwissinfoClient\Hydrator;

use Liip\SwissinfoClient\Exception\HydrationException;
use Liip\SwissinfoClient\Model\ModelInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Hydrate an HTTP response to domain object.
 */
class ModelHydrator implements Hydrator
{
    public function hydrate(ResponseInterface $response, string $class)
    {
        $body = $response->getBody()->getContents();
        if (0 !== mb_strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
            throw new HydrationException('The ModelHydrator cannot hydrate response with Content-Type:'.$response->getHeaderLine('Content-Type'));
        }

        $data = json_decode($body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new HydrationException(sprintf('Error (%d) when trying to json_decode response', json_last_error()));
        }

        $callback = [$class, 'create'];
        if (is_subclass_of($class, ModelInterface::class) && \is_callable($callback)) {
            return $callback($data);
        }

        throw new HydrationException(sprintf('Model %s is does not implement ModelInterface', $class));
    }
}
