<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Tests\Unit\Domain\Factory;

use AdgoalCommon\Alerting\Domain\Entity\AlertingEntity;
use AdgoalCommon\Alerting\Domain\Factory\AlertingFactory;
use AdgoalCommon\Alerting\Tests\Unit\TestCase;
use Exception;

/**
 * Class AlertingFactoryTest.
 *
 * @category Tests\Unit\Domain\Factory
 */
class AlertingFactoryTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @covers       \AdgoalCommon\Alerting\Domain\Factory\AlertingFactory::makeInstance
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
    public function createTest(
        string $id,
        string $projectName,
        string $entityType,
        string $entityUuid,
        string $exceptionType,
        string $exceptionClass,
        string $exceptionMessage
    ): void {
        $factory = new AlertingFactory();
        $alertingEntity = $factory->makeInstance($projectName, $entityType, $entityUuid, $exceptionType, $exceptionClass, $exceptionMessage);
        self::assertInstanceOf(AlertingEntity::class, $alertingEntity);
        self::assertSame($id, $alertingEntity->getId()->toNative());
    }
}
