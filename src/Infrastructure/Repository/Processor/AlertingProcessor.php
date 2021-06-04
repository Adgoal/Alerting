<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Infrastructure\Repository\Processor;

use AdgoalCommon\Alerting\Domain\Entity\AlertingEntity;
use AdgoalCommon\Alerting\Domain\Repository\AlertingRepositoryInterface;
use AdgoalCommon\Alerting\Domain\Repository\Processor\AlertingProcessorInterface;
use AdgoalCommon\Alerting\Domain\Repository\Query\AlertingQueryRepositoryInterface;
use AdgoalCommon\ValueObject\Number\Integer as IntegerValueObject;
use BadMethodCallException;
use DateTime;
use Exception;

/**
 * Class AlertingProcessor.
 *
 * @category Alerting
 *
 * @SuppressWarnings(PHPMD)
 */
class AlertingProcessor implements AlertingProcessorInterface
{
    /**
     * @var AlertingQueryRepositoryInterface
     */
    private $alertaRequestRepository;

    /**
     * @var AlertingRepositoryInterface
     */
    private $alertingRepository;

    /**
     * Minimum time before alerting in seconds.
     *
     * @var int
     */
    private $alertingDelayTime;

    /**
     * AlertingProcessor constructor.
     *
     * @param AlertingQueryRepositoryInterface $alertaRequestRepository
     * @param AlertingRepositoryInterface      $alertingRepository
     * @param int                              $alertingDelayTime
     */
    public function __construct(AlertingQueryRepositoryInterface $alertaRequestRepository, AlertingRepositoryInterface $alertingRepository, int $alertingDelayTime)
    {
        $this->alertaRequestRepository = $alertaRequestRepository;
        $this->alertingRepository = $alertingRepository;
        $this->alertingDelayTime = $alertingDelayTime;
    }

    /**
     * Handle alert.
     *
     * @param AlertingEntity $alertingEntity
     *
     * @return bool
     *
     * @throws Exception
     */
    public function handleAlert(AlertingEntity $alertingEntity): bool
    {
        if (!$this->isNeedToProcessAlert($alertingEntity)) {
            return false;
        }

        $exceptionType = $alertingEntity->getExceptionType()->toNative();
        $method = $this->getHandleMethod($exceptionType);

        if (!method_exists($this, $method)) {
            throw new BadMethodCallException(sprintf("No handle method '%s' for alert '%s'.", $method, $exceptionType));
        }

        $result = $this->$method($alertingEntity);

        if ($result) {
            $alertingEntity->setAlertedAt(new DateTime('now'));
            $alertingEntity->setStatus(IntegerValueObject::fromNative(AlertingEntity::ALERT_STATUS_PROCESSED));
            $this->alertingRepository->store($alertingEntity);
        }

        return true;
    }

    /**
     * Check alert status and when the last alerting was sent.
     *
     * @param AlertingEntity $alertingEntity
     *
     * @return bool
     *
     * @throws Exception
     */
    private function isNeedToProcessAlert(AlertingEntity $alertingEntity): bool
    {
        if (AlertingEntity::ALERT_STATUS_NEW === $alertingEntity->getStatus()->toNative()) {
            return true;
        }

        $updatedAt = $alertingEntity->getUpdateAt();
        $alertedAt = $alertingEntity->getAlertedAt();

        if (null === $alertedAt || null === $updatedAt) {
            return false;
        }

        if ($alertedAt->getTimestamp() > $updatedAt->getTimestamp()) {
            return false;
        }

        $nowDateTime = new DateTime('now');

        return !($alertedAt->getTimestamp() - $nowDateTime->getTimestamp() < $this->alertingDelayTime);
    }

    /**
     * Return handle alert method name.
     *
     * @param string $exceptionType
     *
     * @return string
     */
    private function getHandleMethod(string $exceptionType): string
    {
        return 'handle'.ucfirst(strtolower($exceptionType)).'Alert';
    }

    /**
     * Handle emergency level alert.
     *
     * @param AlertingEntity $alertingEntity
     *
     * @return bool
     */
    private function handleEmergencyAlert(AlertingEntity $alertingEntity): bool
    {
        $exceptionMessage = $alertingEntity->getExceptionMessage()->toNative();

        return $this->alertaRequestRepository->sendAlert($exceptionMessage);
    }

    /**
     * Handle critical level alert.
     *
     * @param AlertingEntity $alertingEntity
     *
     * @return bool
     */
    private function handleCriticalAlert(AlertingEntity $alertingEntity): bool
    {
        $exceptionMessage = $alertingEntity->getExceptionMessage()->toNative();

        return $this->alertaRequestRepository->sendAlert($exceptionMessage);
    }

    /**
     * Handle alert level alert.
     *
     * @param AlertingEntity $alertingEntity
     *
     * @return bool
     */
    private function handleAlertAlert(AlertingEntity $alertingEntity): bool
    {
        $repetitions = $alertingEntity->getRepetitions()->toNative();

        if ($repetitions > AlertingProcessorInterface::EXCEPTION_EMERGENCY_ALERT_REPETITIONS) {
            return $this->handleEmergencyAlert($alertingEntity);
        }

        if ($repetitions > AlertingProcessorInterface::EXCEPTION_CRITICAL_ALERT_REPETITIONS) {
            return $this->handleCriticalAlert($alertingEntity);
        }

        return false;
    }

    /**
     * Handle error level alert.
     *
     * @param AlertingEntity $alertingEntity
     *
     * @return bool
     */
    private function handleErrorAlert(AlertingEntity $alertingEntity): bool
    {
        $repetitions = $alertingEntity->getRepetitions()->toNative();

        if ($repetitions > AlertingProcessorInterface::EXCEPTION_EMERGENCY_ERROR_REPETITIONS) {
            return $this->handleEmergencyAlert($alertingEntity);
        }

        if ($repetitions > AlertingProcessorInterface::EXCEPTION_CRITICAL_ERROR_REPETITIONS) {
            return $this->handleCriticalAlert($alertingEntity);
        }

        return false;
    }
}
