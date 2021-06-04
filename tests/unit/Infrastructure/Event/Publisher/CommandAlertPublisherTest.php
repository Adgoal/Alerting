<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Tests\Unit\Infrastructure\Event\Publisher;

use AdgoalCommon\Alerting\Domain\Entity\AlertingEntity;
use AdgoalCommon\Alerting\Domain\Exception\EventListenerException;
use AdgoalCommon\Alerting\Domain\Factory\AlertingFactory;
use AdgoalCommon\Alerting\Domain\Repository\AlertingRepositoryInterface;
use AdgoalCommon\Alerting\Infrastructure\Event\Publisher\CommandAlertPublisher;
use AdgoalCommon\Alerting\Tests\Unit\TestCase;
use AdgoalCommon\Base\Application\Command\CommandInterface;
use AdgoalCommon\Base\Domain\Exception\ErrorException;
use Enqueue\Client\ProducerInterface;
use League\Event\AbstractListener;
use League\Tactician\CommandEvents\Event\CommandFailed;
use Mockery;
use Mockery\MockInterface;
use Throwable;

/**
 * Class CommandAlertPublisherTest.
 *
 * @category Tests\Unit\Infrastructure\Event\Publisher
 */
class CommandAlertPublisherTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Infrastructure\Event\Publisher\CommandAlertPublisher::handle
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertingDataProvider::getAlertingData
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     *
     * @throws EventListenerException
     * @throws Throwable
     */
    public function handleTest(
        string $id,
        string $projectName,
        string $entityType,
        string $entityUuid,
        string $exceptionType
    ): void {
        $entityMock = $this->makeAlertingEntityMock($id);
        $alertingFactoryMock = $this->makeAlertingFactoryMock($entityMock, $exceptionType);
        $alertingProducerMock = $this->makeAlertingProducerMock();
        $alertingRepositoryMock = $this->makeAlertingRepositoryMock($entityMock);
        $alertEventPublisher = new CommandAlertPublisher($projectName, $alertingFactoryMock, $alertingRepositoryMock, $alertingProducerMock);
        self::assertInstanceOf(AbstractListener::class, $alertEventPublisher);

        $loggerMock = $this->createLoggerMock(['debug' => 0, 'warning' => 0]);
        $alertEventPublisher->setLogger($loggerMock);
        $commandMock = $this->makeCommandMock($entityUuid);
        $eventMock = $this->makeCommandFailedEventMock($commandMock);
        $alertEventPublisher->handle($eventMock);
    }

    /**
     * Make and return AlertingEntity mock object.
     *
     * @return MockInterface|AlertingEntity
     */
    private function makeAlertingEntityMock(string $id): MockInterface
    {
        $entityMock = Mockery::mock(AlertingEntity::class);
        $entityMock
            ->shouldReceive('getId')
            ->times(1)
            ->andReturn($this->createStringLiteralMock($id, 1));

        return $entityMock;
    }

    /**
     * Make and return HttpResponse mock object.
     *
     * @param MockInterface $entityMock
     * @param string        $exceptionType
     *
     * @return MockInterface|AlertingFactory
     */
    private function makeAlertingFactoryMock(MockInterface $entityMock, string $exceptionType): MockInterface
    {
        $alertingFactoryMock = Mockery::mock(AlertingFactory::class);
        $alertingFactoryMock
            ->shouldReceive('getExceptionType')
            ->times(1)
            ->andReturn($this->createStringLiteralMock($exceptionType));
        $alertingFactoryMock
            ->shouldReceive('makeInstance')
            ->times(1)
            ->andReturn($entityMock);

        return $alertingFactoryMock;
    }

    /**
     * Make and return AlertingProducer mock object.
     *
     * @return MockInterface|ProducerInterface
     */
    private function makeAlertingProducerMock(): MockInterface
    {
        $alertingProducerMock = $this->createProducerMock();
        $alertingProducerMock
            ->shouldReceive('sendEvent')
            ->times(1);

        return $alertingProducerMock;
    }

    /**
     * Make and return AlertingRepository mock object.
     *
     * @param MockInterface $entityMock
     *
     * @return MockInterface|AlertingRepositoryInterface
     */
    private function makeAlertingRepositoryMock(MockInterface $entityMock): MockInterface
    {
        $alertingRepositoryMock = Mockery::mock(AlertingRepositoryInterface::class);
        $alertingRepositoryMock
            ->shouldReceive('getById')
            ->times(0)
            ->andReturn($entityMock);
        $alertingRepositoryMock
            ->shouldReceive('store')
            ->times(1);

        return $alertingRepositoryMock;
    }

    /**
     * Make and return Command mock object.
     *
     * @param string $entityUuid
     *
     * @return MockInterface|CommandInterface
     */
    private function makeCommandMock(string $entityUuid): MockInterface
    {
        $commandMock = Mockery::mock(CommandInterface::class);
        $commandMock
            ->shouldReceive('getUuid')
            ->times(1)
            ->andReturn($this->createUuidMock($entityUuid, 1));

        return $commandMock;
    }

    /**
     * Make and return CommandFailedEvent mock object.
     *
     * @param MockInterface $commandMock
     *
     * @return MockInterface|CommandFailed
     */
    private function makeCommandFailedEventMock(MockInterface $commandMock): MockInterface
    {
        $eventMock = Mockery::mock(CommandFailed::class);
        $eventMock
            ->shouldReceive('getCommand')
            ->times(1)
            ->andReturn($commandMock);
        $eventMock
            ->shouldReceive('getException')
            ->times(1)
            ->andReturn(Mockery::mock(ErrorException::class));

        return $eventMock;
    }
}
