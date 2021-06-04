<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Infrastructure\Repository;

use AdgoalCommon\Alerting\Domain\Exception\StorageException;
use AdgoalCommon\Alerting\Domain\Repository\StorageRepositoryInterface;
use AdgoalCommon\Base\Domain\Exception\LoggerException;
use AdgoalCommon\Base\Utils\LoggerTrait;
use Redis;

/**
 * Class AlertingRedisStorageRepository.
 */
class AlertingRedisStorageRepository implements StorageRepositoryInterface
{
    use LoggerTrait;

    /**
     * Redis.
     *
     * @var Redis
     */
    private $redis;

    /**
     * Connection config.
     *
     * @var mixed[]
     */
    private $config;

    /**
     * ProgramResultRepository constructor.
     *
     * @param Redis   $redis
     * @param mixed[] $config
     *
     * @throws LoggerException
     */
    public function __construct(
        Redis $redis,
        array $config
    ) {
        $this->redis = $redis;
        $this->config = $config;
        $this->connect();
    }

    /**
     * @throws LoggerException
     */
    private function connect(): void
    {
        $this->logMessage('Connect to redis instance', LOG_DEBUG);
        $this->redis->connect($this->config['host'], (int) $this->config['port']);
    }

    /**
     * Performs a synchronous save.
     *
     * @return bool TRUE in case of success, FALSE in case of failure.
     *              If a save is already running, this command will fail and return FALSE.
     *
     * @throws StorageException
     */
    public function save(): bool
    {
        if (!$this->redis->save()) {
            throw new StorageException('Data was not save in redis.');
        }

        return true;
    }

    /**
     * Set the string value in argument as value of the key.
     *
     * @param string      $key
     * @param string      $value
     * @param int|mixed[] $timeout
     *
     * @return bool TRUE if the command is successful
     *
     * @throws StorageException
     */
    public function set(string $key, string $value, $timeout = 0): bool
    {
        /** @psalm-suppress PossiblyInvalidArgument */
        $result = $timeout ? $this->redis->set($key, $value, $timeout) : $this->redis->set($key, $value);

        if (!$result) {
            throw new StorageException('Data was not set in redis.');
        }

        return true;
    }

    /**
     * Get the value related to the specified key.
     *
     * @param string $key
     *
     * @return string|false If key didn't exist, FALSE is returned. Otherwise, the value related to this key is returned.
     */
    public function get(string $key)
    {
        return $this->redis->get($key);
    }
}
