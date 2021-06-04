<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Infrastructure\Repository;

use AdgoalCommon\Alerting\Domain\Entity\AlertingEntity;
use AdgoalCommon\Alerting\Domain\Exception\StorageException;
use AdgoalCommon\Alerting\Domain\Factory\AlertingFactory;
use AdgoalCommon\Alerting\Domain\Repository\AlertingRepositoryInterface;
use AdgoalCommon\Alerting\Domain\Repository\StorageRepositoryInterface;
use AdgoalCommon\Base\Domain\Exception\InvalidDataException;
use AdgoalCommon\Base\Domain\Exception\SerializationException;
use AdgoalCommon\Base\Domain\Service\SerializableServiceInterface;
use AdgoalCommon\ValueObject\StringLiteral\StringLiteral;
use Exception;

/**
 * Class AlertingRepository.
 *
 * @category Infrastructure\Repository
 */
class AlertingRepository implements AlertingRepositoryInterface
{
    /**
     * Project name.
     *
     * @var string
     */
    private $projectName;

    /**
     * StorageRepositoryInterface client.
     *
     * @var StorageRepositoryInterface
     */
    private $storage;

    /**
     * @var AlertingFactory
     */
    private $alertingFactory;

    /**
     * @var SerializableServiceInterface
     */
    private $serializableService;

    /**
     * AlertingRepository constructor.
     *
     * @param string                       $projectName
     * @param StorageRepositoryInterface   $storage
     * @param AlertingFactory              $alertingFactory
     * @param SerializableServiceInterface $serializableService
     */
    public function __construct(string $projectName, StorageRepositoryInterface $storage, AlertingFactory $alertingFactory, SerializableServiceInterface $serializableService)
    {
        $this->projectName = $projectName;
        $this->storage = $storage;
        $this->alertingFactory = $alertingFactory;
        $this->serializableService = $serializableService;
    }

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
    public function get(
        StringLiteral $exceptionType,
        StringLiteral $exceptionClass,
        StringLiteral $exceptionMessage
    ): AlertingEntity {
        $data = $this->findOne($exceptionType, $exceptionClass, $exceptionMessage);

        if (!$data) {
            throw new InvalidDataException('Data not found in storage by entity type and uuid!');
        }

        return $this->normalizeDataToEntity($data);
    }

    /**
     * Find and return alert entity from storage by alert id.
     *
     * @param StringLiteral $alertingId
     *
     * @return AlertingEntity
     *
     * @throws InvalidDataException
     */
    public function getById(StringLiteral $alertingId): AlertingEntity
    {
        $data = $this->storage->get($alertingId->toNative());

        if (false === $data) {
            throw new InvalidDataException('Data not found in storage by alert unique id!');
        }
        /* @psalm-suppress PossiblyInvalidArgument */
        return $this->normalizeDataToEntity($data);
    }

    /**
     * Convert serialized string of data to entity object.
     *
     * @param string $data
     *
     * @return AlertingEntity
     *
     * @throws SerializationException
     */
    private function normalizeDataToEntity(string $data): AlertingEntity
    {
        $data = unserialize($data);
        $alertingEntity = $this->serializableService->deserialize($data);

        if (!$alertingEntity instanceof AlertingEntity) {
            throw new SerializationException('Object should be instance of AlertingEntity!');
        }

        return $alertingEntity;
    }

    /**
     * {@inheritdoc}
     */
    public function exists(StringLiteral $exceptionType, StringLiteral $exceptionClass, StringLiteral $exceptionMessage): bool
    {
        return (bool) $this->findOne($exceptionType, $exceptionClass, $exceptionMessage);
    }

    /**
     * Save AlertingEntity to storage, if Alerting already exists increment repetitions.
     *
     * @param AlertingEntity $alertingEntity
     *
     * @return string
     *
     * @throws StorageException
     * @throws Exception
     */
    public function store(AlertingEntity $alertingEntity): string
    {
        $alertingId = $alertingEntity->getId()->toNative();
        $data = $this->storage->get($alertingEntity->getId()->toNative());

        if ($data) {
            $alertingEntity = $this->normalizeDataToEntity($data);
            $alertingEntity->increment();
        }

        if (false === $this->save($alertingId, $alertingEntity)) {
            throw new StorageException('Alert was not saved in storage');
        }

        return $alertingId;
    }

    /**
     * Find entity row in storage by entity type and entity uuid.
     *
     * @param StringLiteral $exceptionType
     * @param StringLiteral $exceptionClass
     * @param StringLiteral $exceptionMessage
     *
     * @return string|null
     *
     * @psalm-suppress InvalidReturnType
     */
    private function findOne(
        StringLiteral $exceptionType,
        StringLiteral $exceptionClass,
        StringLiteral $exceptionMessage
    ): ?string {
        $alertingId = $this->alertingFactory->generateId($this->projectName, $exceptionType, $exceptionClass, $exceptionMessage);

        $result = $this->storage->get($alertingId->toNative());

        if (false === $result) {
            return null;
        }

        return $result;
    }

    /**
     * Save alerting exception to storage.
     *
     * @param string         $alertingId
     * @param AlertingEntity $entity
     *
     * @return bool
     */
    private function save(string $alertingId, AlertingEntity $entity): bool
    {
        $data = $this->serializableService->serialize($entity);
        $this->storage->set($alertingId, serialize($data));

        return $this->storage->save();
    }
}
