<?php
declare(strict_types = 1);

namespace app\models;

use app\helpers\ArrayHelper;
use app\models\user\CurrentUser;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * Class ActiveRecordLogger
 * @package app\models
 * @property integer $id
 * @property-read string $timestamp
 * @property int $user
 * @property string $model
 * @property int $model_key
 * @property array $old_attributes
 * @property array $new_attributes
 *
 */
class ActiveRecordLogger extends ActiveRecord {

	public static function tableName():string {
		return 'sys_log';
	}

	/**
	 * {@inheritDoc}
	 */
	public function rules():array {
		return [
			[['user', 'model_key'], 'integer'],
			[['model'], 'string'],
			[['old_attributes', 'new_attributes'], 'safe']
		];
	}

	/**
	 * @param ActiveRecord $model
	 * @param bool $ignoreUnchanged
	 * @return bool
	 * @throws InvalidConfigException
	 */
	public static function logChanges(ActiveRecord $model, bool $ignoreUnchanged = true):bool {
		if (ArrayHelper::getValue($model, 'loggingEnabled', false)) {
			if (([] === $diff = $model->identifyChangedAttributes()) && $ignoreUnchanged) return true;
			$changedAttributes = array_intersect_key($model->oldAttributes, $diff);
			self::push($model->formName(), $model->primaryKey, $changedAttributes, $diff);
		}
		return true;
	}

	/**
	 * @param string|null $modelName
	 * @param int|null $pKey
	 * @param array $oldAttributes
	 * @param array $newAttributes
	 */
	public static function push(?string $modelName, ?int $pKey, array $oldAttributes, array $newAttributes):void {
		$log = new self([
			'user' => CurrentUser::Id(),
			'model' => $modelName,
			'model_key' => $pKey,
			'old_attributes' => $oldAttributes,
			'new_attributes' => $newAttributes
		]);
		$log->save();
	}

}