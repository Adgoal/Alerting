<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Tests\Unit\Domain\Entity;

use AdgoalCommon\Alerting\Domain\Entity\AlertingEntity;
use AdgoalCommon\Alerting\Tests\Unit\TestCase;
use AdgoalCommon\ValueObject\Number\Integer;
use AdgoalCommon\ValueObject\StringLiteral\StringLiteral;
use DateTime;
use Exception;
use Ramsey\Uuid\Uuid;

/**
 * Class AlertingEntityTest.
 *
 * @category Tests\Unit\Domain\Entity
 */
class AlertingEntityTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::create
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::getId
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertingDataProvider::getAlertingData
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     *
     * @throws Exception
     */
    public function createTest(string $id, string $projectName, string $entityType, string $entityUuid, string $exceptionType, string $exceptionClass, string $exceptionMessage): void
    {
        $alertingEntity = $this->createAlertingEntity($id, $projectName, $entityType, $entityUuid, $exceptionType, $exceptionClass, $exceptionMessage, [1, 0, 0, 0, 0, 0, 0]);

        self::assertInstanceOf(AlertingEntity::class, $alertingEntity);
        self::assertSame($id, $alertingEntity->getId()->toNative());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::serialize
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertingDataProvider::getAlertingData
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     * @param string $serializableAlerting
     *
     * @throws Exception
     */
    public function serializeTest(string $id, string $projectName, string $entityType, string $entityUuid, string $exceptionType, string $exceptionClass, string $exceptionMessage, string $serializableAlerting): void
    {
        $alertingEntity = $this->createAlertingEntity($id, $projectName, $entityType, $entityUuid, $exceptionType, $exceptionClass, $exceptionMessage, [1, 1, 1, 1, 1, 1, 1]);

        $serializableAlerting = unserialize($serializableAlerting);
        unset($serializableAlerting['createdAt']);
        $actualSerializableAlerting = $alertingEntity->serialize();
        unset($actualSerializableAlerting['createdAt']);

        self::assertSame($serializableAlerting, $actualSerializableAlerting);
    }

    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::deserialize
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertingDataProvider::getAlertingData
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     * @param string $serializableAlerting
     *
     * @throws Exception
     */
    public function deserializeTest(string $id, string $projectName, string $entityType, string $entityUuid, string $exceptionType, string $exceptionClass, string $exceptionMessage, string $serializableAlerting): void
    {
        $alertingEntity = $this->createAlertingEntity($id, $projectName, $entityType, $entityUuid, $exceptionType, $exceptionClass, $exceptionMessage, [1, 1, 1, 1, 1, 1, 1]);

        $serializableAlerting = unserialize($serializableAlerting);
        $serializableAlertingEntity = AlertingEntity::deserialize($serializableAlerting);

        $serializableAlerting = $alertingEntity->serialize();
        unset($serializableAlerting['createdAt']);
        $actualSerializableAlerting = $serializableAlertingEntity->serialize();
        unset($actualSerializableAlerting['createdAt']);

        self::assertEquals($actualSerializableAlerting, $serializableAlerting);
    }

    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::getId
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertingDataProvider::getAlertingData
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     *
     * @throws Exception
     */
    public function getIdTest(string $id, string $projectName, string $entityType, string $entityUuid, string $exceptionType, string $exceptionClass, string $exceptionMessage): void
    {
        $alertingEntity = $this->createAlertingEntity($id, $projectName, $entityType, $entityUuid, $exceptionType, $exceptionClass, $exceptionMessage, [1, 0, 0, 0, 0, 0, 0]);
        self::assertInstanceOf(StringLiteral::class, $alertingEntity->getId());
        self::assertSame($id, $alertingEntity->getId()->toNative());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::getProjectName
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertingDataProvider::getAlertingData
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     *
     * @throws Exception
     */
    public function getProjectNameTest(string $id, string $projectName, string $entityType, string $entityUuid, string $exceptionType, string $exceptionClass, string $exceptionMessage): void
    {
        $alertingEntity = $this->createAlertingEntity($id, $projectName, $entityType, $entityUuid, $exceptionType, $exceptionClass, $exceptionMessage, [0, 1, 0, 0, 0, 0, 0]);
        self::assertInstanceOf(StringLiteral::class, $alertingEntity->getProjectName());
        self::assertSame($projectName, $alertingEntity->getProjectName()->toNative());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::getEntityType
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertingDataProvider::getAlertingData
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     *
     * @throws Exception
     */
    public function getEntityTypeTest(string $id, string $projectName, string $entityType, string $entityUuid, string $exceptionType, string $exceptionClass, string $exceptionMessage): void
    {
        $alertingEntity = $this->createAlertingEntity($id, $projectName, $entityType, $entityUuid, $exceptionType, $exceptionClass, $exceptionMessage, [0, 0, 1, 0, 0, 0, 0]);
        self::assertInstanceOf(StringLiteral::class, $alertingEntity->getEntityType());
        self::assertSame($entityType, $alertingEntity->getEntityType()->toNative());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::getEntityUuid
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertingDataProvider::getAlertingData
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     *
     * @throws Exception
     */
    public function getEntityUuidTest(string $id, string $projectName, string $entityType, string $entityUuid, string $exceptionType, string $exceptionClass, string $exceptionMessage): void
    {
        $alertingEntity = $this->createAlertingEntity($id, $projectName, $entityType, $entityUuid, $exceptionType, $exceptionClass, $exceptionMessage, [0, 0, 0, 1, 0, 0, 0]);
        self::assertInstanceOf(Uuid::class, $alertingEntity->getEntityUuid());
        self::assertSame($entityUuid, $alertingEntity->getEntityUuid()->toString());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::getExceptionType
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertingDataProvider::getAlertingData
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     *
     * @throws Exception
     */
    public function getExceptionTypeTest(string $id, string $projectName, string $entityType, string $entityUuid, string $exceptionType, string $exceptionClass, string $exceptionMessage): void
    {
        $alertingEntity = $this->createAlertingEntity($id, $projectName, $entityType, $entityUuid, $exceptionType, $exceptionClass, $exceptionMessage, [0, 0, 0, 0, 1, 0, 0]);
        self::assertInstanceOf(StringLiteral::class, $alertingEntity->getExceptionType());
        self::assertSame($exceptionType, $alertingEntity->getExceptionType()->toNative());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::getExceptionClass
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertingDataProvider::getAlertingData
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     *
     * @throws Exception
     */
    public function getExceptionClassTest(string $id, string $projectName, string $entityType, string $entityUuid, string $exceptionType, string $exceptionClass, string $exceptionMessage): void
    {
        $alertingEntity = $this->createAlertingEntity($id, $projectName, $entityType, $entityUuid, $exceptionType, $exceptionClass, $exceptionMessage, [0, 0, 0, 0, 0, 1, 0, 0]);
        self::assertInstanceOf(StringLiteral::class, $alertingEntity->getExceptionClass());
        self::assertSame($exceptionClass, $alertingEntity->getExceptionClass()->toNative());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::getExceptionMessage
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertingDataProvider::getAlertingData
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     *
     * @throws Exception
     */
    public function getExceptionMessageTest(string $id, string $projectName, string $entityType, string $entityUuid, string $exceptionType, string $exceptionClass, string $exceptionMessage): void
    {
        $alertingEntity = $this->createAlertingEntity($id, $projectName, $entityType, $entityUuid, $exceptionType, $exceptionClass, $exceptionMessage, [0, 0, 0, 0, 0, 0, 1]);
        self::assertInstanceOf(StringLiteral::class, $alertingEntity->getExceptionMessage());
        self::assertSame($exceptionMessage, $alertingEntity->getExceptionMessage()->toNative());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::setStatus
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::getStatus
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertingDataProvider::getAlertingData
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     *
     * @throws Exception
     */
    public function getStatusTest(string $id, string $projectName, string $entityType, string $entityUuid, string $exceptionType, string $exceptionClass, string $exceptionMessage): void
    {
        $alertingEntity = $this->createAlertingEntity($id, $projectName, $entityType, $entityUuid, $exceptionType, $exceptionClass, $exceptionMessage, [0, 0, 0, 0, 0, 0, 0]);
        self::assertInstanceOf(Integer::class, $alertingEntity->getStatus());
        self::assertSame(AlertingEntity::ALERT_STATUS_NEW, $alertingEntity->getStatus()->toNative());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::getRepetitions
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::increment
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertingDataProvider::getAlertingData
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     *
     * @throws Exception
     */
    public function incrementTest(string $id, string $projectName, string $entityType, string $entityUuid, string $exceptionType, string $exceptionClass, string $exceptionMessage): void
    {
        $alertingEntity = $this->createAlertingEntity($id, $projectName, $entityType, $entityUuid, $exceptionType, $exceptionClass, $exceptionMessage, [0, 0, 0, 0, 0, 0, 0]);

        $repetitionsValueObject = $alertingEntity->getRepetitions();
        $repetitions = $repetitionsValueObject->toNative();
        self::assertInstanceOf(Integer::class, $repetitionsValueObject);
        $alertingEntity->increment();
        ++$repetitions;
        self::assertSame($repetitions, $repetitionsValueObject->toNative());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::getCreatedAt
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertingDataProvider::getAlertingData
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     *
     * @throws Exception
     */
    public function getCreatedAtTest(string $id, string $projectName, string $entityType, string $entityUuid, string $exceptionType, string $exceptionClass, string $exceptionMessage): void
    {
        $alertingEntity = $this->createAlertingEntity($id, $projectName, $entityType, $entityUuid, $exceptionType, $exceptionClass, $exceptionMessage, [0, 0, 0, 0, 0, 0, 0]);
        self::assertInstanceOf(DateTime::class, $alertingEntity->getCreatedAt());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::getUpdateAt
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertingDataProvider::getAlertingData
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     *
     * @throws Exception
     */
    public function getUpdateAtTest(string $id, string $projectName, string $entityType, string $entityUuid, string $exceptionType, string $exceptionClass, string $exceptionMessage): void
    {
        $alertingEntity = $this->createAlertingEntity($id, $projectName, $entityType, $entityUuid, $exceptionType, $exceptionClass, $exceptionMessage, [0, 0, 0, 0, 0, 0, 0]);
        self::assertNull($alertingEntity->getUpdateAt());
        $alertingEntity->increment();
        self::assertInstanceOf(DateTime::class, $alertingEntity->getUpdateAt());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::setAlertedAt
     * @covers       \AdgoalCommon\Alerting\Domain\Entity\AlertingEntity::getAlertedAt
     *
     * @dataProvider \AdgoalCommon\Alerting\Tests\Unit\DataProvider\AlertingDataProvider::getAlertingData
     *
     * @param string $id
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     *
     * @throws Exception
     */
    public function getAlertedAtTest(string $id, string $projectName, string $entityType, string $entityUuid, string $exceptionType, string $exceptionClass, string $exceptionMessage): void
    {
        $alertingEntity = $this->createAlertingEntity($id, $projectName, $entityType, $entityUuid, $exceptionType, $exceptionClass, $exceptionMessage, [0, 0, 0, 0, 0, 0, 0]);
        self::assertNull($alertingEntity->getAlertedAt());
        $alertingEntity->setAlertedAt(new DateTime('now'));
        self::assertInstanceOf(DateTime::class, $alertingEntity->getAlertedAt());
    }
}
