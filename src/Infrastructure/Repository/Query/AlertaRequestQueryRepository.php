<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Infrastructure\Repository\Query;

use AdgoalCommon\Alerting\Domain\Repository\Query\AlertingQueryRepositoryInterface;
use AdgoalCommon\Base\Domain\Adapter\HttpRequestInterface;
use AdgoalCommon\Base\Domain\Exception\InvalidResponseContentTypeException;
use AdgoalCommon\Base\Domain\Exception\InvalidResponseStatusCodeException;
use AdgoalCommon\Base\Domain\Exception\InvalidResponseStatusException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use function GuzzleHttp\Psr7\stream_for;

/**
 * Class AlertaRequestRepository.
 *
 * @category Infrastructure\Repository\Query
 *
 * @SuppressWarnings(PHPMD)
 */
class AlertaRequestQueryRepository implements AlertingQueryRepositoryInterface
{
    private const ALERTA_ATTRIBUTES_TEMPLATE = [
        'attributes' => [
            'region' => 'EU',
        ],
        'correlate' => [
            'HttpServerError',
            'HttpServerOK',
        ],
        'environment' => 'Production',
        'event' => 'HttpServerError',
        'group' => 'Web',
        'origin' => 'curl',
        'resource' => 'default',
        'service' => [],
        'severity' => 'major',
        'tags' => [
            'dc1',
        ],
        'text' => '',
        'type' => 'exceptionAlert',
        'value' => '',
    ];

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @var HttpRequestInterface
     */
    private $httpRequestAdapter;

    /**
     * Alerta request attributes.
     *
     * @var mixed[]
     */
    private $attributes;

    /**
     * RequestRepositoryRepository constructor.
     *
     * @param ClientInterface         $client
     * @param RequestFactoryInterface $requestFactory
     * @param HttpRequestInterface    $httpRequestAdapter
     * @param mixed[]                 $attributes
     */
    public function __construct(
        ClientInterface $client,
        RequestFactoryInterface $requestFactory,
        HttpRequestInterface $httpRequestAdapter,
        array $attributes = []
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->httpRequestAdapter = $httpRequestAdapter;
        $this->attributes = $attributes;
    }

    /**
     * Build http request, send it to affiliate network and evaluate http response.
     *
     * @param string  $alertMessage
     * @param mixed[] $attributes
     *
     * @return bool
     *
     * @throws InvalidResponseContentTypeException
     * @throws InvalidResponseStatusCodeException
     * @throws InvalidResponseStatusException
     * @throws ClientExceptionInterface
     */
    public function sendAlert(string $alertMessage, array $attributes = []): bool
    {
        $httpRequest = $this->buildRequest($alertMessage, $attributes);
        $httpResponse = $this->client->sendRequest($httpRequest);

        if (HttpRequestInterface::RESPONSE_STATUS_CREATED !== $httpResponse->getStatusCode()) {
            throw new InvalidResponseStatusCodeException('Got invalid status code in response.');
        }

        $result = (string) $httpResponse->getBody();
        $result = json_decode($result, true);

        if (!$result) {
            throw new InvalidResponseContentTypeException('Invalid response content type.');
        }

        if (isset($result['alert']['status']) && self::ALERTA_RESPONSE_STATUS_OPEN !== $result['alert']['status']) {
            throw new InvalidResponseStatusException('Alert status should be open');
        }

        return true;
    }

    /**
     * Create and initialize http request.
     *
     * @param string  $alertMessage
     * @param mixed[] $attributes
     *
     * @return RequestInterface
     */
    private function buildRequest(string $alertMessage, array $attributes = []): RequestInterface
    {
        $url = $this->httpRequestAdapter->getUrl();
        $request = $this->requestFactory->createRequest(HttpRequestInterface::REQUEST_METHOD_POST, $url);
        $headers = $this->httpRequestAdapter->getHeaders();

        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        $attributes = array_merge($this->attributes, $attributes);

        return $request->withBody($this->getBody($alertMessage, $attributes));
    }

    /**
     * Create json stream from alerta request body template.
     *
     * @param string  $alertMessage
     * @param mixed[] $attributes
     *
     * @return StreamInterface
     * @psalm-suppress DeprecatedFunction
     */
    private function getBody(string $alertMessage, array $attributes = []): StreamInterface
    {
        $body = $this->generateRequestAttributes($attributes);
        $body['text'] = $alertMessage;

        return stream_for(json_encode($body));
    }

    /**
     * Generate alerta request attributes.
     *
     * @param mixed[] $attributes
     *
     * @return mixed[]
     */
    private function generateRequestAttributes(array $attributes = []): array
    {
        $request = self::ALERTA_ATTRIBUTES_TEMPLATE;

        if (array_key_exists('resource', $attributes)) {
            $request['resource'] = $attributes['resource'];
        }

        if (array_key_exists('service', $attributes)) {
            if (is_string($attributes['service'])) {
                $attributes['service'] = [$attributes['service']];
            }
            $request['service'] = $attributes['service'];
        }

        if (array_key_exists('environment', $attributes)) {
            $request['environment'] = $attributes['environment'];
        }

        if (array_key_exists('type', $attributes)) {
            $request['type'] = $attributes['type'];
        }

        if (array_key_exists('group', $attributes)) {
            $request['group'] = $attributes['group'];
        }

        if (array_key_exists('severity', $attributes)) {
            $request['severity'] = $attributes['severity'];
        }

        if (array_key_exists('tags', $attributes)) {
            if (is_string($attributes['tags'])) {
                $attributes['tags'] = [$attributes['tags']];
            }
            $request['tags'] = $attributes['tags'];
        }

        if (array_key_exists('attributes', $attributes)) {
            $request['attributes'] = $attributes['attributes'];
        }

        return $request;
    }
}
