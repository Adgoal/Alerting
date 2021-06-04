<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Tests\Unit\Infrastructure\Adapter;

use AdgoalCommon\Alerting\Infrastructure\Adapter\AlertaHttpRequestAdapter;
use AdgoalCommon\Alerting\Tests\Unit\TestCase;

/**
 * Class AlertaHttpRequestAdapterTest.
 *
 * @category Tests\Unit\Infrastructure\Adapter
 */
class AlertaHttpRequestAdapterTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Infrastructure\Adapter\AlertaHttpRequestAdapter::getUrl
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertaDataProvider::getAlertaCredentials()
     *
     * @param string $alertaHost
     * @param string $alertaToken
     * @param string $alertaUrl
     */
    public function getUrlTest(string $alertaHost, string $alertaToken, string $alertaUrl): void
    {
        $alertaHttpRequestAdapter = new AlertaHttpRequestAdapter($alertaHost, $alertaToken);
        self::assertSame($alertaUrl, $alertaHttpRequestAdapter->getUrl());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Infrastructure\Adapter\AlertaHttpRequestAdapter::getHeaders
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertaDataProvider::getAlertaCredentials()
     *
     * @param string  $alertaHost
     * @param string  $alertaToken
     * @param string  $alertaUrl
     * @param mixed[] $alertaHeaders
     */
    public function getHeadersTest(string $alertaHost, string $alertaToken, string $alertaUrl, array $alertaHeaders): void
    {
        $alertaHttpRequestAdapter = new AlertaHttpRequestAdapter($alertaHost, $alertaToken);
        self::assertSame($alertaHeaders, $alertaHttpRequestAdapter->getHeaders());
    }
}
