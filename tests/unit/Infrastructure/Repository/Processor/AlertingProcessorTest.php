<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Tests\Unit\Infrastructure\Repository\Processor;

use AdgoalCommon\Alerting\Domain\Repository\AlertingRepositoryInterface;
use AdgoalCommon\Alerting\Domain\Repository\Processor\AlertingProcessorInterface;
use AdgoalCommon\Alerting\Domain\Repository\Query\AlertingQueryRepositoryInterface;
use AdgoalCommon\Alerting\Infrastructure\Repository\Processor\AlertingProcessor;
use AdgoalCommon\Alerting\Tests\Unit\TestCase;
use Exception;
use Mockery;
use Mockery\MockInterface;

/**
 * Class AlertingProcessorTest.
 *
 * @category Tests\Unit\Infrastructure\Event\Consumer
 */
class AlertingProcessorTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Infrastructure\Repository\Processor\AlertingProcessor::handleAlert
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertingDataProvider::getAlertingData
     *
     * @param string  $id
     * @param string  $projectName
     * @param string  $entityType
     * @param string  $entityUuid
     * @param string  $exceptionType
     * @param string  $exceptionClass
     * @param string  $exceptionMessage
     * @param string  $serializable
     * @param int     $repetitions
     * @param int     $status
     * @param string  $createdAt
     * @param string  $updatedAt
     * @param string  $alertedAt
     * @param mixed[] $calledTimes
     *
     * @throws Exception
     */
    public function handleAlertTest(
        string $id,
        string $projectName,
        string $entityType,
        string $entityUuid,
        string $exceptionType,
        string $exceptionClass,
        string $exceptionMessage,
        string $serializable,
        int $repetitions,
        int $status,
        string $createdAt,
        string $updatedAt,
        string $alertedAt,
        array $calledTimes
    ): void {
        $entityMock = $this->createAlertingEntityMock(
            $id,
            $projectName,
            $entityType,
            $entityUuid,
            $exceptionType,
            $exceptionClass,
            $exceptionMessage,
            $repetitions,
            $status,
            $createdAt,
            $updatedAt,
            $alertedAt,
            $calledTimes
        );

        $alertingQueryRepositoryMock = $this->makeAlertingQueryRepositoryMock($calledTimes['sendAlert']);
        $alertingRepositoryMock = $this->makeAlertingRepositoryMock($calledTimes['store']);
        $alertingProcessor = new AlertingProcessor($alertingQueryRepositoryMock, $alertingRepositoryMock, 10);

        self::assertInstanceOf(AlertingProcessorInterface::class, $alertingProcessor);
        self::assertTrue($alertingProcessor->handleAlert($entityMock));
    }

    /**
     * Return AlertingQueryRepositoryInterface mock object.
     *
     * @param int $times
     *
     * @return MockInterface|AlertingQueryRepositoryInterface
     */
    private function makeAlertingQueryRepositoryMock(int $times): MockInterface
    {
        $alertingQueryRepositoryMock = Mockery::mock(AlertingQueryRepositoryInterface::class);
        $alertingQueryRepositoryMock
            ->shouldReceive('sendAlert')
            ->times($times)
            ->andReturn(true);

        return $alertingQueryRepositoryMock;
    }

    /**
     * Return AlertingRepository mock object.
     *
     * @param int $times
     *
     * @return MockInterface|AlertingRepositoryInterface
     */
    private function makeAlertingRepositoryMock(int $times): MockInterface
    {
        $alertingRepositoryMock = Mockery::mock(AlertingRepositoryInterface::class);
        $alertingRepositoryMock
            ->shouldReceive('store')
            ->times($times)
            ->andReturn(true);

        return $alertingRepositoryMock;
    }
}
