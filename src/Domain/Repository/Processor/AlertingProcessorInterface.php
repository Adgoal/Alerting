<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Domain\Repository\Processor;

use AdgoalCommon\Alerting\Domain\Entity\AlertingEntity;

/**
 * Interface AlertingProcessorInterface.
 *
 * @category Alerting
 */
interface AlertingProcessorInterface
{
    public const EXCEPTION_TYPE_EMERGENCY = 'emergency';
    public const EXCEPTION_TYPE_ALERT = 'alert';
    public const EXCEPTION_TYPE_CRITICAL = 'critical';
    public const EXCEPTION_TYPE_ERROR = 'error';

    public const EXCEPTION_CRITICAL_ALERT_REPETITIONS = 3;
    public const EXCEPTION_EMERGENCY_ALERT_REPETITIONS = 5;

    public const EXCEPTION_CRITICAL_ERROR_REPETITIONS = 5;
    public const EXCEPTION_EMERGENCY_ERROR_REPETITIONS = 8;

    public function handleAlert(AlertingEntity $alertingEntity): bool;
}
