<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Domain\Entity;

use AdgoalCommon\Base\Domain\Entity\SerializableInterface;
use AdgoalCommon\ValueObject\Number\Integer as IntegerValueObject;
use AdgoalCommon\ValueObject\StringLiteral\StringLiteral;
use Assert\Assertion;
use Assert\AssertionFailedException;
use DateTime;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class AlertingEntity.
 *
 * @category Domain\Entity
 */
class AlertingEntity implements SerializableInterface
{
    public const ALERT_STATUS_NEW = 0;
    public const ALERT_STATUS_PROCESSED = 1;

    /**
     * @var StringLiteral
     */
    private $id;

    /**
     * @var StringLiteral
     */
    private $entityType;

    /**
     * @var UuidInterface
     */
    private $entityUuid;

    /**
     * @var StringLiteral
     */
    private $projectName;

    /**
     * @var StringLiteral
     */
    private $exceptionType;

    /**
     * @var StringLiteral
     */
    private $exceptionClass;

    /**
     * @var StringLiteral
     */
    private $exceptionMessage;

    /**
     * @var IntegerValueObject
     */
    private $status;

    /**
     * @var IntegerValueObject
     */
    private $repetitions;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var DateTime
     */
    private $updateAt;

    /**
     * @var DateTime
     */
    private $alertedAt;

    /**
     * AlertingEntity constructor.
     *
     * @param StringLiteral $id
     * @param StringLiteral $projectName
     * @param StringLiteral $entityType
     * @param UuidInterface $entityUuid
     * @param StringLiteral $exceptionType
     * @param StringLiteral $exceptionClass
     * @param StringLiteral $exceptionMessage
     */
    private function __construct(
        StringLiteral $id,
        StringLiteral $projectName,
        StringLiteral $entityType,
        UuidInterface $entityUuid,
        StringLiteral $exceptionType,
        StringLiteral $exceptionClass,
        StringLiteral $exceptionMessage
    ) {
        $this->id = $id;
        $this->projectName = $projectName;
        $this->entityType = $entityType;
        $this->entityUuid = $entityUuid;
        $this->exceptionType = $exceptionType;
        $this->exceptionClass = $exceptionClass;
        $this->exceptionMessage = $exceptionMessage;
    }

    /**
     * Create new AlertingEntity.
     *
     * @param StringLiteral $id
     * @param StringLiteral $projectName
     * @param StringLiteral $entityType
     * @param UuidInterface $entityUuid
     * @param StringLiteral $exceptionType
     * @param StringLiteral $exceptionClass
     * @param StringLiteral $exceptionMessage
     *
     * @return AlertingEntity
     *
     * @throws Exception
     */
    public static function create(
        StringLiteral $id,
        StringLiteral $projectName,
        StringLiteral $entityType,
        UuidInterface $entityUuid,
        StringLiteral $exceptionType,
        StringLiteral $exceptionClass,
        StringLiteral $exceptionMessage
    ): self {
        $alertingEntity = new static($id, $projectName, $entityType, $entityUuid, $exceptionType, $exceptionClass, $exceptionMessage);
        $alertingEntity->createdAt = new DateTime('now');
        $alertingEntity->repetitions = IntegerValueObject::fromNative(1);
        $alertingEntity->setStatus(IntegerValueObject::fromNative(self::ALERT_STATUS_NEW));

        return $alertingEntity;
    }

    /**
     * Return alert unique id.
     *
     * @return StringLiteral
     */
    public function getId(): StringLiteral
    {
        return $this->id;
    }

    /**
     * Return EntityType.
     *
     * @return StringLiteral
     */
    public function getEntityType(): StringLiteral
    {
        return $this->entityType;
    }

    /**
     * Return ProjectName.
     *
     * @return StringLiteral
     */
    public function getProjectName(): StringLiteral
    {
        return $this->projectName;
    }

    /**
     * Return ExceptionClass.
     *
     * @return StringLiteral
     */
    public function getExceptionClass(): StringLiteral
    {
        return $this->exceptionClass;
    }

    /**
     * Return EntityUuid.
     *
     * @return UuidInterface
     */
    public function getEntityUuid(): UuidInterface
    {
        return $this->entityUuid;
    }

    /**
     * Return exceptionType.
     *
     * @return StringLiteral
     */
    public function getExceptionType(): StringLiteral
    {
        return $this->exceptionType;
    }

    /**
     * Set exceptionType.
     *
     * @param StringLiteral $exceptionType
     *
     * @return $this
     */
    public function setExceptionType(StringLiteral $exceptionType): self
    {
        $this->exceptionType = $exceptionType;

        return $this;
    }

    /**
     * Return ExceptionMessage.
     *
     * @return StringLiteral
     */
    public function getExceptionMessage(): StringLiteral
    {
        return $this->exceptionMessage;
    }

    /**
     * Return Status.
     *
     * @return IntegerValueObject
     */
    public function getStatus(): IntegerValueObject
    {
        return $this->status;
    }

    /**
     * Set status.
     *
     * @param IntegerValueObject $status
     *
     * @return $this
     */
    public function setStatus(IntegerValueObject $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Return Repetitions.
     *
     * @return IntegerValueObject
     */
    public function getRepetitions(): IntegerValueObject
    {
        return $this->repetitions;
    }

    /**
     * Increment repetitions.
     *
     * @return $this
     *
     * @throws Exception
     */
    public function increment(): self
    {
        $this->repetitions->inc();
        $this->updateAt = new DateTime('now');

        return $this;
    }

    /**
     * Return CreatedAt.
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * Return UpdateAt.
     *
     * @return DateTime|null
     */
    public function getUpdateAt(): ?DateTime
    {
        return $this->updateAt;
    }

    /**
     * Return AlertedAt.
     *
     * @return DateTime|null
     */
    public function getAlertedAt(): ?DateTime
    {
        return $this->alertedAt;
    }

    /**
     * Set alertedAt.
     *
     * @param DateTime $alertedAt
     *
     * @return $this
     */
    public function setAlertedAt(DateTime $alertedAt): self
    {
        $this->alertedAt = $alertedAt;

        return $this;
    }

    /**
     * Initialize entity from data array.
     *
     * @param mixed[] $data
     *
     * @throws AssertionFailedException
     *
     * @return AlertingEntity
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'id');
        Assertion::keyExists($data, 'projectName');
        Assertion::keyExists($data, 'entityType');
        Assertion::keyExists($data, 'entityUuid');
        Assertion::keyExists($data, 'exceptionType');
        Assertion::keyExists($data, 'exceptionClass');
        Assertion::keyExists($data, 'exceptionMessage');
        Assertion::keyExists($data, 'repetitions');
        Assertion::keyExists($data, 'status');
        Assertion::keyExists($data, 'createdAt');
        Assertion::keyExists($data, 'updatedAt');
        Assertion::keyExists($data, 'alertedAt');

        $alertingEntity = new static(
            StringLiteral::fromNative($data['id']),
            StringLiteral::fromNative($data['projectName']),
            StringLiteral::fromNative($data['entityType']),
            Uuid::fromString($data['entityUuid']),
            StringLiteral::fromNative($data['exceptionType']),
            StringLiteral::fromNative($data['exceptionClass']),
            StringLiteral::fromNative($data['exceptionMessage'])
        );

        $alertingEntity->repetitions = IntegerValueObject::fromNative($data['repetitions']);
        $alertingEntity->setStatus(IntegerValueObject::fromNative($data['status']));
        $alertingEntity->createdAt = $data['createdAt'];
        $alertingEntity->updateAt = $data['updatedAt'];
        $alertingEntity->alertedAt = $data['alertedAt'];

        return $alertingEntity;
    }

    /**
     * Convert entity object to array.
     *
     * @return mixed[]
     */
    public function serialize(): array
    {
        return [
            'id' => $this->getId()->toNative(),
            'projectName' => $this->getProjectName()->toNative(),
            'entityType' => $this->getEntityType()->toNative(),
            'entityUuid' => $this->getEntityUuid()->toString(),
            'exceptionType' => $this->getExceptionType()->toNative(),
            'exceptionClass' => $this->getExceptionClass()->toNative(),
            'exceptionMessage' => $this->getExceptionMessage()->toNative(),
            'repetitions' => $this->getRepetitions()->toNative(),
            'status' => $this->getStatus()->toNative(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdateAt(),
            'alertedAt' => $this->getAlertedAt(),
        ];
    }
}
