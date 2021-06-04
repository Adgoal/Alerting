<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Tests\Unit;

use AdgoalCommon\Alerting\Domain\Entity\AlertingEntity;
use AdgoalCommon\ValueObject\Number\Integer as ValueObjectInteger;
use AdgoalCommon\ValueObject\StringLiteral\StringLiteral;
use DateTime;
use Enqueue\Client\ProducerInterface;
use Exception;
use Interop\Queue\Context;
use Interop\Queue\Message;
use Mockery;
use Mockery\MockInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class TestCase extends \PHPUnit\Framework\TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /**
     * Create AlertingEntity object.
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     * @param int[]  $times
     *
     * @return AlertingEntity
     *
     * @throws Exception
     */
    protected function createAlertingEntity(
        string $id,
        string $projectName,
        string $entityType,
        string $entityUuid,
        string $exceptionType,
        string $exceptionClass,
        string $exceptionMessage,
        array $times = []): AlertingEntity
    {
        $idValueObject = $this->createStringLiteralMock($id, $times[0]);
        $projectNameValueObject = $this->createStringLiteralMock($projectName, $times[1]);
        $entityTypeValueObject = $this->createStringLiteralMock($entityType, $times[2]);
        $entityUuidValueObject = $this->createUuidMock($entityUuid, $times[3]);
        $exceptionTypeValueObject = $this->createStringLiteralMock($exceptionType, $times[4]);
        $exceptionClassValueObject = $this->createStringLiteralMock($exceptionClass, $times[5]);
        $exceptionMessageValueObject = $this->createStringLiteralMock($exceptionMessage, $times[6]);

        return AlertingEntity::create($idValueObject, $projectNameValueObject, $entityTypeValueObject, $entityUuidValueObject, $exceptionTypeValueObject, $exceptionClassValueObject, $exceptionMessageValueObject);
    }

    /**
     * Create AlertingEntity object.
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     * @param int    $repetitions
     * @param int    $status
     * @param string $createdAt
     * @param string $updatedAt
     * @param string $alertedAt
     * @param int[]  $calledTimes
     *
     * @return MockInterface|AlertingEntity
     *
     * @throws Exception
     */
    protected function createAlertingEntityMock(
        string $id,
        string $projectName,
        string $entityType,
        string $entityUuid,
        string $exceptionType,
        string $exceptionClass,
        string $exceptionMessage,
        int $repetitions,
        int $status,
        string $createdAt,
        string $updatedAt,
        string $alertedAt,
        array $calledTimes = []
    ): MockInterface {
        $entityMock = Mockery::mock(AlertingEntity::class);

        $idValueObject = $this->createStringLiteralMock($id, $calledTimes['getId']);
        $projectNameValueObject = $this->createStringLiteralMock($projectName, $calledTimes['getProjectName']);
        $entityTypeValueObject = $this->createStringLiteralMock($entityType, $calledTimes['getEntityType']);
        $entityUuidValueObject = $this->createUuidMock($entityUuid, $calledTimes['getEntityUuid']);
        $exceptionTypeValueObject = $this->createStringLiteralMock($exceptionType, $calledTimes['getExceptionType']);
        $exceptionClassValueObject = $this->createStringLiteralMock($exceptionClass, $calledTimes['getExceptionClass']);
        $exceptionMessageValueObject = $this->createStringLiteralMock($exceptionMessage, $calledTimes['getExceptionMessage']);
        $repetitionsValueObject = $this->createIntegerMock($repetitions, $calledTimes['getRepetitions']);
        $statusValueObject = $this->createIntegerMock($status, $calledTimes['getStatus']);

        $entityMock
            ->shouldReceive('getId')
            ->times($calledTimes['getId'])
            ->andReturn($idValueObject);

        $entityMock
            ->shouldReceive('getProjectName')
            ->times($calledTimes['getProjectName'])
            ->andReturn($projectNameValueObject);

        $entityMock
            ->shouldReceive('getEntityType')
            ->times($calledTimes['getEntityType'])
            ->andReturn($entityTypeValueObject);

        $entityMock
            ->shouldReceive('getEntityUuid')
            ->times($calledTimes['getEntityUuid'])
            ->andReturn($entityUuidValueObject);

        $entityMock
            ->shouldReceive('getExceptionType')
            ->times($calledTimes['getExceptionType'])
            ->andReturn($exceptionTypeValueObject);

        $entityMock
            ->shouldReceive('getExceptionClass')
            ->times($calledTimes['getExceptionClass'])
            ->andReturn($exceptionClassValueObject);

        $entityMock
            ->shouldReceive('getExceptionMessage')
            ->times($calledTimes['getExceptionMessage'])
            ->andReturn($exceptionMessageValueObject);

        $entityMock
            ->shouldReceive('getRepetitions')
            ->times($calledTimes['getRepetitions'])
            ->andReturn($repetitionsValueObject);

        $entityMock
            ->shouldReceive('getStatus')
            ->times($calledTimes['getStatus'])
            ->andReturn($statusValueObject);

        $entityMock
            ->shouldReceive('setStatus')
            ->times($calledTimes['setStatus']);

        $entityMock
            ->shouldReceive('getCreatedAt')
            ->times($calledTimes['getCreatedAt'])
            ->andReturn(new DateTime($createdAt));

        $entityMock
            ->shouldReceive('getUpdatedAt')
            ->times($calledTimes['getUpdatedAt'])
            ->andReturn(new DateTime($updatedAt));

        $entityMock
            ->shouldReceive('getAlertedAt')
            ->times($calledTimes['getAlertedAt'])
            ->andReturn(new DateTime($alertedAt));

        $entityMock
            ->shouldReceive('setAlertedAt')
            ->times($calledTimes['setAlertedAt']);

        return $entityMock;
    }

    /**
     * Create Uuid mock.
     *
     * @param string $uuid
     * @param int    $times
     *
     * @return MockInterface|Uuid
     */
    protected function createUuidMock(string $uuid, int $times = 0): MockInterface
    {
        $mock = Mockery::mock(Uuid::class);
        $mock
            ->shouldReceive('toString')
            ->times($times)
            ->andReturn($uuid);

        return $mock;
    }

    /**
     * Create StringLiteral mock.
     *
     * @param string $string
     * @param int    $times
     *
     * @return MockInterface|StringLiteral
     */
    protected function createStringLiteralMock(string $string, int $times = 0): MockInterface
    {
        $mock = Mockery::mock(StringLiteral::class);
        $mock
            ->shouldReceive('toNative')
            ->times($times)
            ->andReturn($string);

        return $mock;
    }

    /**
     * Create Integer mock.
     *
     * @param int $integer
     * @param int $times
     *
     * @return MockInterface|ValueObjectInteger
     */
    protected function createIntegerMock(int $integer, int $times = 0): MockInterface
    {
        $mock = Mockery::mock(ValueObjectInteger::class);
        $mock
            ->shouldReceive('toNative')
            ->times($times)
            ->andReturn($integer);

        $mock
            ->shouldReceive('inc')
            ->times(0)
            ->andReturn(++$integer);

        return $mock;
    }

    /**
     * Create Enqueue producer mock.
     *
     * @return MockInterface|ProducerInterface
     */
    protected function createProducerMock(): MockInterface
    {
        return Mockery::mock(ProducerInterface::class);
    }

    /**
     * Create Enqueue message mock.
     *
     * @param string $message
     *
     * @return MockInterface|Message
     */
    protected function createEnqueueMessageMock(string $message): MockInterface
    {
        $mock = Mockery::mock(Message::class);
        $mock
            ->shouldReceive('getBody')
            ->times(1)
            ->andReturn($message);

        return $mock;
    }

    /**
     * Create Enqueue traceable producer mock.
     *
     * @return MockInterface|Context
     */
    protected function createEnqueueContextMock(): MockInterface
    {
        return Mockery::mock(Context::class);
    }

    /**
     * Create LoggerInterface mock.
     *
     * @param mixed[] $times
     *
     * @return MockInterface|LoggerInterface
     */
    protected function createLoggerMock(array $times): MockInterface
    {
        $loggerMock = Mockery::mock(LoggerInterface::class);
        $loggerMock
            ->shouldReceive('warning')
            ->times($times['warning'])
            ->andReturn('');
        $loggerMock
            ->shouldReceive('debug')
            ->times($times['debug'])
            ->andReturn('');

        return $loggerMock;
    }

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $this->startMockery();
    }

    /**
     * This method is called after each test.
     */
    protected function tearDown(): void
    {
        $this->closeMockery();
    }
}
