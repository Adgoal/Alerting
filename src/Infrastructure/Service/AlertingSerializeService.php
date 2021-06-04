<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Infrastructure\Service;

use AdgoalCommon\Alerting\Domain\Entity\AlertingEntity;
use AdgoalCommon\Base\Domain\Entity\SerializableInterface;
use AdgoalCommon\Base\Domain\Exception\SerializationException;
use AdgoalCommon\Base\Domain\Service\SerializableServiceInterface;
use Assert\AssertionFailedException;

/**
 * Class AlertingSerializeService.
 *
 * @category Infrastructure\Service
 */
class AlertingSerializeService implements SerializableServiceInterface
{
    /**
     * Factory method for initialize new AlertingEntity object.
     *
     * @param mixed $data
     *
     * @throws AssertionFailedException
     * @throws AssertionFailedException
     * @throws AssertionFailedException
     *
     * @return AlertingEntity
     */
    public function deserialize($data): object
    {
        return AlertingEntity::deserialize($data);
    }

    /**
     * Factory method to transform DTO to array.
     *
     * @param object $object
     *
     * @return mixed
     *
     * @throws SerializationException
     */
    public function serialize(object $object)
    {
        if ($object instanceof SerializableInterface) {
            return $object->serialize();
        }

        throw new SerializationException(sprintf('Object \'%s\' cannot be serialize!', get_class($object)));
    }
}
