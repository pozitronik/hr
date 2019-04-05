<?php
declare(strict_types = 1);

namespace app\models\core;

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
 * @property-read null|object $modelClass Отдаёт загруженную модели по имени из $model
 */
interface ActiveRecordLoggerInterface {

	/**
	 * Отдаёт загруженную модели по имени из $model
	 * @return object|null
	 */
	public function getModelClass():?object ;
}