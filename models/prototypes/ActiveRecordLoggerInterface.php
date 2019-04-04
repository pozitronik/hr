<?php
declare(strict_types = 1);

namespace app\models\prototypes;

/**
 * Interface ActiveRecordLoggerInterface
 * @package app\models\prototypes
 *
 * @property-read string $timestamp
 * @property int $user
 * @property string $model
 * @property int $model_key
 * @property array $old_attributes
 * @property array $new_attributes
 */
interface ActiveRecordLoggerInterface {
}