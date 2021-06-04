<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Infrastructure\Event\Publisher;

use AdgoalCommon\Alerting\Application\Processor\AlertEventProcessor;
use AdgoalCommon\Alerting\Domain\Exception\EventListenerException;
use AdgoalCommon\Alerting\Domain\Factory\AlertingFactory;
use AdgoalCommon\Alerting\Domain\Repository\AlertingRepositoryInterface;
use AdgoalCommon\Base\Application\Command\CommandInterface;
use AdgoalCommon\Base\Utils\LoggerTrait;
use AdgoalCommon\ValueObject\StringLiteral\StringLiteral;
use Enqueue\Client\ProducerInterface;
use League\Event\AbstractListener;
use League\Event\EventInterface;
use League\Tactician\CommandEvents\Event\CommandFailed;
use Throwable;

/**
 * Class CommandAlertPublisher.
 *
 * @category Domain\Event\Publisher
 *
 * @SuppressWarnings(PHPMD)
 */
class CommandAlertPublisher extends AbstractListener
{
    use LoggerTrait;

    /**
     * Project name.
     *
     * @var string
     */
    private $projectName;

    /**
     * @var ProducerInterface
     */
    private $queueProducer;

    /**
     * @var AlertingFactory
     */
    private $alertingFactory;

    /**
     * @var AlertingRepositoryInterface
     */
    private $alertingRepository;

    /**
     * CommandAlertPublisher constructor.
     *
     * @param string                      $projectName
     * @param AlertingFactory             $alertingFactory
     * @param AlertingRepositoryInterface $alertingRepository
     * @param ProducerInterface           $queueProducer
     */
    public function __construct(string $projectName, AlertingFactory $alertingFactory, AlertingRepositoryInterface $alertingRepository, ProducerInterface $queueProducer)
    {
        $this->projectName = $projectName;
        $this->alertingFactory = $alertingFactory;
        $this->alertingRepository = $alertingRepository;
        $this->queueProducer = $queueProducer;
    }

    /**
     * Handle an event.
     *
     * @param EventInterface $event
     *
     * @throws EventListenerException
     * @throws Throwable
     */
    public function handle(EventInterface $event): void
    {
        if (!$event instanceof CommandFailed) {
            throw new EventListenerException('Event not instance of '.CommandFailed::class);
        }
        $command = $event->getCommand();

        if (!$command instanceof CommandInterface) {
            throw new EventListenerException('Command not instance of '.CommandInterface::class);
        }
        $alertId = $this->save($command, $event->getException());
        $this->produce($alertId);
    }

    /**
     * Initialize and save alert entity.
     *
     * @param CommandInterface $command
     * @param Throwable        $exception
     *
     * @return StringLiteral
     *
     * @throws Throwable
     */
    private function save(CommandInterface $command, Throwable $exception): StringLiteral
    {
        $entityId = $command->getUuid();
        $entityType = get_class($command);
        $exceptionType = $this->alertingFactory->getExceptionType($exception);
        $exceptionMessage = $this->getExceptionMessage($exception);
        $alertingEntity = $this->alertingFactory->makeInstance($this->projectName, $entityType, $entityId->toString(), $exceptionType, get_class($exception), $exceptionMessage);
        $this->alertingRepository->store($alertingEntity);

        return $alertingEntity->getId();
    }

    /**
     * Send alert id to queue.
     *
     * @param StringLiteral $alertId
     */
    private function produce(StringLiteral $alertId): void
    {
        $this->queueProducer->sendEvent(AlertEventProcessor::QUEUE_COMMAND_FAILED_ALERT, $alertId->toNative());
    }
}
