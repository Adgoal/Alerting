<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Domain\Repository;

use AdgoalCommon\Alerting\Domain\Entity\AlertingEntity;
use AdgoalCommon\Base\Domain\Exception\InvalidDataException;
use AdgoalCommon\ValueObject\StringLiteral\StringLiteral;

/**
 * Interface AlertingRepositoryInterface.
 *
 * @category Domain\Repository
 */
interface AlertingRepositoryInterface
{
    /**
     * Find and return entity exception from storage.
     *
     * @param StringLiteral $exceptionType
     * @param StringLiteral $exceptionClass
     * @param StringLiteral $exceptionMessage
     *
     * @return AlertingEntity
     *
     * @throws InvalidDataException
     */
    public function get(StringLiteral $exceptionType, StringLiteral $exceptionClass, StringLiteral $exceptionMessage): AlertingEntity;

    /**
     * Find and return alert entity from storage by alert id.
     *
     * @param StringLiteral $alertingId
     *
     * @return AlertingEntity
     */
    public function getById(StringLiteral $alertingId): AlertingEntity;

    /**
     * Check if alert entity exists in storage.
     *
     * @param StringLiteral $exceptionType
     * @param StringLiteral $exceptionClass
     * @param StringLiteral $exceptionMessage
     *
     * @return bool
     */
    public function exists(StringLiteral $exceptionType, StringLiteral $exceptionClass, StringLiteral $exceptionMessage): bool;

    /**
     * Save AlertingEntity last uncommitted events.
     *
     * @param AlertingEntity $alertingEntity
     *
     * @return string
     */
    public function store(AlertingEntity $alertingEntity): string;
}
