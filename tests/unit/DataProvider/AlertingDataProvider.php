<?php

declare(strict_types=1);

namespace AdgoalCommon\Alerting\Tests\Unit\DataProvider;

use Exception;

/**
 * Class AlertingDataProvider.
 *
 * @category Tests\Unit\DataProvider
 */
class AlertingDataProvider
{
    /**
     * Return alert data fixture.
     *
     * @return mixed[]
     *
     * @throws Exception
     */
    public function getAlertingData(): array
    {
        return [
            [
                '0f07097658b257e87e06ea4ca17203e2911c63e1',
                'adgoal-alerting',
                'DataGate\\AffiliateNetwork\\ShareASale\\Application\\Command\\Program\\Validate\\ValidateCommand',
                'd299918d-8428-44e0-883e-47003ab99563',
                'error',
                'DataGate\\AffiliateNetwork\\ShareASale\\Domain\\Exception\\Program\\NotJoinedException',
                'Uncaught PHP Exception DataGate\\AffiliateNetwork\\ShareASale\\Domain\\Exception\\Program\\NotJoinedException: "Program with id \'83500\' not approved." at /app/src/Domain/Entity/ProgramEntity.php line 195',
                'a:12:{s:2:"id";s:40:"0f07097658b257e87e06ea4ca17203e2911c63e1";s:11:"projectName";s:15:"adgoal-alerting";s:10:"entityType";s:89:"DataGate\AffiliateNetwork\ShareASale\Application\Command\Program\Validate\ValidateCommand";s:10:"entityUuid";s:36:"d299918d-8428-44e0-883e-47003ab99563";s:13:"exceptionType";s:5:"error";s:14:"exceptionClass";s:80:"DataGate\AffiliateNetwork\ShareASale\Domain\Exception\Program\NotJoinedException";s:16:"exceptionMessage";s:197:"Uncaught PHP Exception DataGate\AffiliateNetwork\ShareASale\Domain\Exception\Program\NotJoinedException: "Program with id \'83500\' not approved." at /app/src/Domain/Entity/ProgramEntity.php line 195";s:11:"repetitions";i:1;s:6:"status";i:0;s:9:"createdAt";O:8:"DateTime":3:{s:4:"date";s:26:"2019-06-04 16:45:53.629462";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:9:"updatedAt";N;s:9:"alertedAt";N;}',
                1,
                0,
                '2019-03-29 20:10:00',
                '2019-03-29 20:10:00',
                '2019-03-29 20:10:00',
                ['getId' => 0, 'getProjectName' => 0, 'getEntityType' => 0, 'getEntityUuid' => 0, 'getExceptionType' => 1, 'getExceptionClass' => 0, 'getExceptionMessage' => 0, 'getRepetitions' => 1, 'getStatus' => 1, 'setStatus' => 0, 'getCreatedAt' => 0, 'getUpdatedAt' => 0, 'getAlertedAt' => 0, 'setAlertedAt' => 0, 'sendAlert' => 0, 'store' => 0],
            ],
            [
                '4bb5d24c1de4dc9a5fbc702199197c05ba708849',
                'adgoal-alerting',
                '\\Application\\Command\\ProgramCollection\\Aggregate\\AggregateCommand',
                'd785bed6-1659-4ad7-9def-962862af53fc',
                'error',
                'TypeError',
                'Uncaught PHP Exception TypeError: "Argument 1 passed to \\Application\\Command\\Program\\Run\\RunCommand::__construct() must be of the type int, string given, called in /Application/Command/ProgramCollectionSaga.php on line 186" at /Application/Command/Program/Run/RunCommand.php line 38',
                'a:12:{s:2:"id";s:40:"4bb5d24c1de4dc9a5fbc702199197c05ba708849";s:11:"projectName";s:15:"adgoal-alerting";s:10:"entityType";s:65:"\Application\Command\ProgramCollection\Aggregate\AggregateCommand";s:10:"entityUuid";s:36:"d785bed6-1659-4ad7-9def-962862af53fc";s:13:"exceptionType";s:5:"error";s:14:"exceptionClass";s:9:"TypeError";s:16:"exceptionMessage";s:282:"Uncaught PHP Exception TypeError: "Argument 1 passed to \Application\Command\Program\Run\RunCommand::__construct() must be of the type int, string given, called in /Application/Command/ProgramCollectionSaga.php on line 186" at /Application/Command/Program/Run/RunCommand.php line 38";s:11:"repetitions";i:1;s:6:"status";i:0;s:9:"createdAt";O:8:"DateTime":3:{s:4:"date";s:26:"2019-06-04 16:46:25.664478";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:9:"updatedAt";N;s:9:"alertedAt";N;}',
                1,
                0,
                '2019-03-29 20:10:00',
                '2019-03-29 20:10:00',
                '2019-03-29 20:10:00',
                ['getId' => 0, 'getProjectName' => 0, 'getEntityType' => 0, 'getEntityUuid' => 0, 'getExceptionType' => 1, 'getExceptionClass' => 0, 'getExceptionMessage' => 0, 'getRepetitions' => 1, 'getStatus' => 1, 'setStatus' => 0, 'getCreatedAt' => 0, 'getUpdatedAt' => 0, 'getAlertedAt' => 0, 'setAlertedAt' => 0, 'sendAlert' => 0, 'store' => 0],
            ],
            [
                '5739a43c8713952a0a86a8fd7734729b52b3fc24',
                'adgoal-alerting',
                '\\Application\\Command\\ProgramCollection\\Aggregate\\AggregateCommand',
                '7bd58997-cbd4-4a48-a068-f821d8408391',
                'critical',
                'CriticalException',
                'Uncaught PHP Exception CriticalException: "test critical" at /Application/Command/Program/Run/RunCommand.php line 41',
                'a:12:{s:2:"id";s:40:"5739a43c8713952a0a86a8fd7734729b52b3fc24";s:11:"projectName";s:15:"adgoal-alerting";s:10:"entityType";s:65:"\Application\Command\ProgramCollection\Aggregate\AggregateCommand";s:10:"entityUuid";s:36:"7bd58997-cbd4-4a48-a068-f821d8408391";s:13:"exceptionType";s:8:"critical";s:14:"exceptionClass";s:17:"CriticalException";s:16:"exceptionMessage";s:116:"Uncaught PHP Exception CriticalException: "test critical" at /Application/Command/Program/Run/RunCommand.php line 41";s:11:"repetitions";i:1;s:6:"status";i:0;s:9:"createdAt";O:8:"DateTime":3:{s:4:"date";s:26:"2019-06-04 16:46:55.779700";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:9:"updatedAt";N;s:9:"alertedAt";N;}',
                1,
                0,
                '2019-03-29 20:10:00',
                '2019-03-29 20:10:00',
                '2019-03-29 20:10:00',
                ['getId' => 0, 'getProjectName' => 0, 'getEntityType' => 0, 'getEntityUuid' => 0, 'getExceptionType' => 1, 'getExceptionClass' => 0, 'getExceptionMessage' => 1, 'getRepetitions' => 0, 'getStatus' => 1, 'setStatus' => 1, 'getCreatedAt' => 0, 'getUpdatedAt' => 0, 'getAlertedAt' => 0, 'setAlertedAt' => 1, 'sendAlert' => 1, 'store' => 1],
            ],
            [
                '8056a679af74b2a4f6b4d69af9d36bbd55d8a9f2',
                'adgoal-alerting',
                '\\Application\\Command\\ProgramCollection\\Aggregate\\AggregateCommand',
                'd975d108-4b46-4561-b5f1-c812fcf8e146',
                'emergency',
                'EmergencyException',
                'Uncaught PHP Exception EmergencyException: "test critical" at /Application/Command/Program/Run/RunCommand.php line 42',
                'a:12:{s:2:"id";s:40:"8056a679af74b2a4f6b4d69af9d36bbd55d8a9f2";s:11:"projectName";s:15:"adgoal-alerting";s:10:"entityType";s:65:"\Application\Command\ProgramCollection\Aggregate\AggregateCommand";s:10:"entityUuid";s:36:"d975d108-4b46-4561-b5f1-c812fcf8e146";s:13:"exceptionType";s:9:"emergency";s:14:"exceptionClass";s:18:"EmergencyException";s:16:"exceptionMessage";s:117:"Uncaught PHP Exception EmergencyException: "test critical" at /Application/Command/Program/Run/RunCommand.php line 42";s:11:"repetitions";i:1;s:6:"status";i:0;s:9:"createdAt";O:8:"DateTime":3:{s:4:"date";s:26:"2019-06-04 16:47:23.404003";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:9:"updatedAt";N;s:9:"alertedAt";N;}',
                1,
                0,
                '2019-03-29 20:10:00',
                '2019-03-29 20:10:00',
                '2019-03-29 20:10:00',
                ['getId' => 0, 'getProjectName' => 0, 'getEntityType' => 0, 'getEntityUuid' => 0, 'getExceptionType' => 1, 'getExceptionClass' => 0, 'getExceptionMessage' => 1, 'getRepetitions' => 0, 'getStatus' => 1, 'setStatus' => 1, 'getCreatedAt' => 0, 'getUpdatedAt' => 0, 'getAlertedAt' => 0, 'setAlertedAt' => 1, 'sendAlert' => 1, 'store' => 1],
            ],
        ];
    }
}
