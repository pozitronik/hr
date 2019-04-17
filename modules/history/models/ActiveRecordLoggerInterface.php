<?php
declare(strict_types = 1);

namespace app\modules\history\models;

/**
 * Interface ActiveRecordLoggerInterface
 *
 * @property-read string $timestamp
 * @property int $user
 * @property string $model
 * @property int $model_key
 * @property array $old_attributes
 * @property array $new_attributes
 *
 * @property int $event_type
 */
interface ActiveRecordLoggerInterface {

}