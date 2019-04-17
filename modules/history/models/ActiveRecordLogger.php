<?php
declare(strict_types = 1);

namespace app\modules\history\models;

use app\helpers\ArrayHelper;
use app\models\user\CurrentUser;
use Throwable;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * Class ActiveRecordLogger
 * @property integer $id
 * @property-read string $at
 * @property-read string $timestamp//alias of $at
 * @property int $user
 * @property string $model
 * @property int $model_key
 * @property array $old_attributes
 * @property array $new_attributes
 * @property-read int $event_type
 */
class ActiveRecordLogger extends ActiveRecord implements ActiveRecordLoggerInterface {

	/**
	 * {@inheritDoc}
	 */
	public static function tableName():string {
		return 'sys_log';
	}

	public function attributeLabels() {
		return [
			'id' => 'ID',
			'at' => 'Время события',
			'timestamp' => 'Время события',
			'user' => 'Пользователь',
			'model' => 'Источник',
			'old_attributes' => 'Было',
			'new_attributes' => 'Стало',
			'event_type' => 'Тип события'
		];
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
	 * @throws Throwable
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
	 * @param ActiveRecord $model
	 * @throws InvalidConfigException
	 */
	public static function logModel(ActiveRecord $model):void {
		self::push($model->formName(), $model->primaryKey, [], $model->attributes);
	}

	/**
	 * Логирует удаление модели
	 * @param ActiveRecord $model
	 * @throws InvalidConfigException
	 */
	public static function logDelete(ActiveRecord $model):void {
		self::push($model->formName(), $model->primaryKey, $model->attributes, []);
	}

	/**
	 * @param string|null $modelName
	 * @param mixed $pKey
	 * @param array $oldAttributes
	 * @param array $newAttributes
	 */
	private static function push(?string $modelName, $pKey, array $oldAttributes, array $newAttributes):void {
		$pKey = is_numeric($pKey)?$pKey:null;//$pKey может быть массивом

		$log = new self([
			'user' => CurrentUser::Id(),
			'model' => $modelName,
			'model_key' => $pKey,
			'old_attributes' => $oldAttributes,
			'new_attributes' => $newAttributes
		]);
		$log->save();
	}

	/**
	 * @return string
	 */
	public function getTimestamp():string {
		return $this->at;
	}

	/**
	 * @return int
	 */
	public function getEventType():int {
		if ([] === $this->old_attributes) return HistoryEvent::EVENT_CREATED;
		if ([] === $this->new_attributes) return HistoryEvent::EVENT_DELETED;
		return HistoryEvent::EVENT_CHANGED;
	}

}