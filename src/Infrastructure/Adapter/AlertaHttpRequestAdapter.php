<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Infrastructure\Adapter;

use AdgoalCommon\Base\Domain\Adapter\HttpRequestInterface;

/**
 * Class AlertaHttpRequestAdapter.
 *
 * @category Infrastructure\Adapter
 */
final class AlertaHttpRequestAdapter implements HttpRequestInterface
{
    /**
     * @var string
     */
    private $alertaHost;

    /**
     * Auth token.
     *
     * @var string
     */
    private $alertaToken;

    /**
     * AlertaHttpRequestAdapter constructor.
     *
     * @param string $alertaHost
     * @param string $alertaToken
     */
    public function __construct(string $alertaHost, string $alertaToken)
    {
        $this->alertaHost = $alertaHost;
        $this->alertaToken = $alertaToken;
    }

    /**
     * Generate and return alerta service url.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->alertaHost;
    }

    /**
     * Generate and return alerta service headers.
     *
     * @return string[]
     */
    public function getHeaders(): array
    {
        return ['Authorization' => 'Key '.$this->alertaToken, 'Content-type' => self::RESPONSE_HEADER_CONTENT_TYPE_JSON];
    }
}
