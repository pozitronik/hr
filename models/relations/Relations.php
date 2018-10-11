<?php
declare(strict_types = 1);

namespace app\models\relations;

use app\helpers\ArrayHelper;
use Throwable;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * Trait Relations
 * Функции, общеприменимые ко всем таблицам связей.
 * @package app\models\relations
 */
trait Relations {

	/**
	 * Линкует в этом релейшене две модели. Модели могут быть заданы как через айдишники
	 * @param ActiveRecord|integer $master
	 * @param ActiveRecord|integer $slave
	 * @throws Throwable
	 */
	public static function linkModel($master, $slave):void {
		if (empty($master) || empty($slave)) return;

		$link = new self();

		$first_name = ArrayHelper::getValue($link->rules(), '0.0.0', new Exception('Не удалось получить атрибут для связи'));
		$second_name = ArrayHelper::getValue($link->rules(), '0.0.1', new Exception('Не удалось получить атрибут для связи'));

		$link->$first_name = is_numeric($master)?$master:$master->primaryKey;
		$link->$second_name = is_numeric($slave)?$slave:$slave->primaryKey;
		$link->save();//save or update, whatever
	}

	/**
	 * Линкует в этом релейшене две модели. Модели могут быть заданы как через айдишники, так и напрямую, в виде массивов или так.
	 * @param integer|ActiveRecord|integer[]|ActiveRecord[] $master
	 * @param integer|ActiveRecord|integer[]|ActiveRecord[] $slave
	 * @throws Throwable
	 */
	public static function linkModels($master, $slave):void {
		if (empty($master) || empty($slave)) return;
		if (is_array($master)) {
			foreach ($master as $master_item) {
				if (is_array($slave)) {
					foreach ($slave as $slave_item) {
						self::linkModel($master_item, $slave_item);
					}
				} else self::linkModel($master_item, $slave);
			}
		} else if (is_array($slave)) {
			foreach ($slave as $slave_item) {
				self::linkModel($master, $slave_item);
			}
		} else self::linkModel($master, $slave);
	}

	/**
	 * Удаляет связь между моделями в этом релейшене
	 * @param integer|ActiveRecord|integer[]|ActiveRecord[] $master
	 * @param integer|ActiveRecord|integer[]|ActiveRecord[] $slave
	 * @throws Throwable
	 */
	public static function unlinkModels($master, $slave):void {
		if (empty($master) || empty($slave)) return;
		$link = new self();
		$first_name = ArrayHelper::getValue($link->rules(), '0.0.0', new Exception('Не удалось получить атрибут для связи'));
		$second_name = ArrayHelper::getValue($link->rules(), '0.0.1', new Exception('Не удалось получить атрибут для связи'));

		if (is_array($master)) {
			if (!is_numeric(ArrayHelper::getValue($master, 0))) {//suppose it is ActiveRecord[]
				$master = ArrayHelper::getColumn($master, 'id');
			}
		} else {
			$master = is_numeric($master)?(int)$master:$master->primaryKey;
		}

		if (is_array($slave)) {
			if (!is_numeric(ArrayHelper::getValue($slave, 0))) {//suppose it is ActiveRecord[]
				$slave = ArrayHelper::getColumn($slave, 'id');
			}
		} else {
			$slave = is_numeric($slave)?(int)$slave:$slave->primaryKey;
		}

		self::deleteAll([$first_name => $master, $second_name => $slave]);
	}
}