<?php

declare(strict_types=1);

namespace Liip\SwissinfoClient;

use function GuzzleHttp\Psr7\build_query;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Liip\SwissinfoClient\Exception\APIException;
use Liip\SwissinfoClient\Exception\Exception;
use Liip\SwissinfoClient\Hydrator\Hydrator;
use Liip\SwissinfoClient\Hydrator\ModelHydrator;
use Liip\SwissinfoClient\Model\PageDetail;
use Psr\Http\Message\ResponseInterface;

class Client
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var Hydrator
     */
    private $hydrator;

    public function __construct(HttpClient $client, MessageFactory $messageFactory)
    {
        $this->client = $client;
        $this->messageFactory = $messageFactory;
        $this->hydrator = new ModelHydrator();
    }

    /**
     * @throws APIException
     */
    public function getPageDetail(string $pageId): PageDetail
    {
        $request = $this->messageFactory->createRequest('GET', $this->getApiUri('detail/'.$pageId));
        $response = $this->client->sendRequest($request);

        return $this->handleResponse($response, PageDetail::class);
    }

    /**
     * Handle responses from the endpoint: handle errors and hydrations.
     *
     * @throws Exception
     *
     * @return mixed Hydration return data
     */
    protected function handleResponse(ResponseInterface $response, string $responseClass)
    {
        $statusCode = $response->getStatusCode();
        if (200 !== $statusCode && 201 !== $statusCode) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, $responseClass);
    }

    /**
     * Handle HTTP errors.
     *
     * Call is controlled by the specific API methods.
     *
     * @throws APIException
     */
    protected function handleErrors(ResponseInterface $response): void
    {
        throw new APIException($response->getReasonPhrase());
    }

    private function getApiUri(string $apiAction, array $params = []): string
    {
        $uri = '/'.$apiAction;
        if (!empty($params)) {
            $uri .= '?'.build_query($params);
        }

        return $uri;
    }
}
