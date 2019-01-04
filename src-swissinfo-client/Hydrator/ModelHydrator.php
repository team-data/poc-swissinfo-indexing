<?php

declare(strict_types=1);

namespace Liip\SwissinfoClient\Hydrator;

use Liip\SwissinfoClient\Exception\HydrationException;
use Liip\SwissinfoClient\Model\ModelInterface;
use Pnz\JsonException\Json;
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

        try {
            $data = Json::decode($body, true);
        } catch (\JsonException $exception) {
            throw new HydrationException(
                'Error when trying to decode response:',
                $exception->getCode(),
                $exception
            );
        }

        $callback = [$class, 'create'];
        if (is_subclass_of($class, ModelInterface::class) && \is_callable($callback)) {
            return $callback($data);
        }

        throw new HydrationException(sprintf('Model %s is does not implement ModelInterface', $class));
    }
}
