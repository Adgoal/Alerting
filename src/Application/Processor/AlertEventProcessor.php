<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Application\Processor;

use AdgoalCommon\Alerting\Domain\Repository\AlertingRepositoryInterface;
use AdgoalCommon\Alerting\Domain\Repository\Processor\AlertingProcessorInterface;
use AdgoalCommon\Base\Utils\LoggerTrait;
use AdgoalCommon\ValueObject\StringLiteral\StringLiteral;
use Enqueue\Client\TopicSubscriberInterface;
use Exception;
use Interop\Queue\Context;
use Interop\Queue\Message;
use Interop\Queue\Processor;
use Throwable;

/**
 * Class AlertEventConsumer.
 *
 * @category Domain\Event\Consumer
 */
class AlertEventProcessor implements Processor, TopicSubscriberInterface
{
    use LoggerTrait;

    public const QUEUE_COMMAND_FAILED_ALERT = 'log.command.failed.alert';

    /**
     * @var AlertingProcessorInterface
     */
    private $alertingProcessor;

    /**
     * Alerting repository object.
     *
     * @var AlertingRepositoryInterface
     */
    private $alertingRepository;

    /**
     * AlertEventConsumer constructor.
     *
     * @param AlertingProcessorInterface  $alertingProcessor
     * @param AlertingRepositoryInterface $alertingRepository
     */
    public function __construct(AlertingProcessorInterface $alertingProcessor, AlertingRepositoryInterface $alertingRepository)
    {
        $this->alertingProcessor = $alertingProcessor;
        $this->alertingRepository = $alertingRepository;
    }

    /**
     * Process enqueue message.
     *
     * @param Message $message
     * @param Context $context
     *
     * @return object|string
     *
     * @throws Exception
     */
    public function process(Message $message, Context $context)
    {
        $this->logMessage('Consume alert event', LOG_DEBUG);
        $alertId = $message->getBody();

        try {
            $alertEntity = $this->alertingRepository->getById(StringLiteral::fromNative($alertId));
            $this->alertingProcessor->handleAlert($alertEntity);
        } catch (Throwable $exception) {
            $this->logMessage(sprintf('Consume error event with Exception: %s, %s', get_class($exception), $exception->getMessage()), LOG_DEBUG);
        }

        return self::ACK;
    }

    public static function getSubscribedTopics()
    {
        return self::QUEUE_COMMAND_FAILED_ALERT;
    }
}
