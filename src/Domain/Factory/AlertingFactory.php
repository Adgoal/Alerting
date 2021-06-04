<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Domain\Factory;

use AdgoalCommon\Alerting\Domain\Entity\AlertingEntity;
use AdgoalCommon\Alerting\Domain\Repository\Processor\AlertingProcessorInterface;
use AdgoalCommon\Base\Domain\Exception\AlertException;
use AdgoalCommon\Base\Domain\Exception\CriticalException;
use AdgoalCommon\Base\Domain\Exception\EmergencyException;
use AdgoalCommon\Base\Domain\Exception\ErrorException;
use AdgoalCommon\ValueObject\StringLiteral\StringLiteral;
use Exception;
use Ramsey\Uuid\Uuid;
use Throwable;

/**
 * Class AlertingFactory.
 *
 * @category Domain\Factory
 */
class AlertingFactory
{
    /**
     * Factory method for initialize new AlertingEntity object.
     *
     * @param string $projectName
     * @param string $entityType
     * @param string $entityUuid
     * @param string $exceptionType
     * @param string $exceptionClass
     * @param string $exceptionMessage
     *
     * @return AlertingEntity
     *
     * @throws Exception
     */
    public function makeInstance(
        string $projectName,
        string $entityType,
        string $entityUuid,
        string $exceptionType,
        string $exceptionClass,
        string $exceptionMessage
    ): AlertingEntity {
        $entityTypeVO = StringLiteral::fromNative($entityType);
        $entityUuidVO = Uuid::fromString($entityUuid);
        $exceptionTypeVO = StringLiteral::fromNative($exceptionType);
        $exceptionClassVO = StringLiteral::fromNative($exceptionClass);
        $exceptionMessageVO = StringLiteral::fromNative($exceptionMessage);
        $projectNameVO = StringLiteral::fromNative($projectName);
        $id = $this->generateId($projectName, $exceptionTypeVO, $exceptionClassVO, $exceptionMessageVO);

        return AlertingEntity::create($id, $projectNameVO, $entityTypeVO, $entityUuidVO, $exceptionTypeVO, $exceptionClassVO, $exceptionMessageVO);
    }

    /**
     * Generate unique exception hash id from project name, exception type, exception class and exception message.
     *
     * @param string        $projectName
     * @param StringLiteral $exceptionType
     * @param StringLiteral $exceptionClass
     * @param StringLiteral $exceptionMessage
     *
     * @return StringLiteral
     */
    public function generateId(
        string $projectName,
        StringLiteral $exceptionType,
        StringLiteral $exceptionClass,
        StringLiteral $exceptionMessage
    ): StringLiteral {
        return StringLiteral::fromNative(sha1($projectName.$exceptionType->toNative().$exceptionClass->toNative().$exceptionMessage->toNative()));
    }

    /**
     * Define exception type from exception object.
     *
     * @param Throwable $exception
     *
     * @return string
     */
    public function getExceptionType(Throwable $exception): string
    {
        switch (true) {
            case $exception instanceof EmergencyException:
                return AlertingProcessorInterface::EXCEPTION_TYPE_EMERGENCY;

            case $exception instanceof CriticalException:
                return AlertingProcessorInterface::EXCEPTION_TYPE_CRITICAL;

            case $exception instanceof AlertException:
                return AlertingProcessorInterface::EXCEPTION_TYPE_ALERT;

            case $exception instanceof ErrorException:
                return AlertingProcessorInterface::EXCEPTION_TYPE_ERROR;
        }

        return AlertingProcessorInterface::EXCEPTION_TYPE_ERROR;
    }
}
