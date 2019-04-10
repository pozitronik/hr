<?php
declare(strict_types = 1);

namespace app\models\core;

use app\helpers\ArrayHelper;
use app\models\user\CurrentUser;
use ReflectionException;
use Throwable;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\UnknownClassException;
use yii\db\ActiveRecord;

/**
 * Class ActiveRecordLogger
 * @package app\models
 * @property integer $id
 * @property-read string $at
 * @property-read string $timestamp//alias of $at
 * @property int $user
 * @property string $model
 * @property int $model_key
 * @property array $old_attributes
 * @property array $new_attributes
 *
 * @property-read null|Model $modelClass
 *
 */
class ActiveRecordLogger extends ActiveRecord implements ActiveRecordLoggerInterface {

	/**
	 * {@inheritDoc}
	 */
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
	 * Логирует удаление моделей по условию
	 * @param ActiveRecord $model
	 * @param array $deleteConditions //Выбросит аяяяй, если сюда пойдёт строка. Пока оставляю так, этот метод ещё будет меняться
	 * @throws InvalidConfigException
	 */
	public static function logDeleteAll(ActiveRecord $model, array $deleteConditions):void {
		self::push($model->formName(), null, [], $deleteConditions);
	}

	/**
	 * @param string|null $modelName
	 * @param mixed $pKey
	 * @param array $oldAttributes
	 * @param array $newAttributes
	 */
	public static function push(?string $modelName, $pKey, array $oldAttributes, array $newAttributes):void {
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
	 * @return null|object
	 * @throws Throwable
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public function getModelClass():?object {
		return Magic::LoadClassByName($this->model);
	}

}