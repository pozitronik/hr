<?php
declare(strict_types = 1);

namespace app\components\pozitronik\core\traits;

use app\components\pozitronik\helpers\ArrayHelper;
use Throwable;
use yii\base\Exception;
use yii\base\Model;

/**
 * Trait Relations
 * Функции, общеприменимые ко всем таблицам связей.
 * @package app\models\relations
 */
trait Relations {

	/**
	 * Линкует в этом релейшене две модели. Модели могут быть заданы как через айдишники, так и моделью, и ещё тупо строкой
	 * @param Model|int|string $master
	 * @param Model|int|string $slave
	 * @throws Throwable
	 */
	public static function linkModel($master, $slave):void {
		if (empty($master) || empty($slave)) return;

		$link = new self();

		$first_name = ArrayHelper::getValue($link->rules(), '0.0.0', new Exception('Не удалось получить атрибут для связи'));
		$second_name = ArrayHelper::getValue($link->rules(), '0.0.1', new Exception('Не удалось получить атрибут для связи'));

		if (is_numeric($master)) {
			$link->$first_name = (int)$master;
		} elseif (is_object($master)) {
			$link->$first_name = ArrayHelper::getValue($master, 'primaryKey', new Exception("Класс {$master->formName()} не имеет атрибута primaryKey"));
		} else $link->$first_name = (string)$master; //suppose it string field name

		if (is_numeric($slave)) {
			$link->$second_name = (int)$slave;
		} elseif (is_object($slave)) {
			$link->$second_name = ArrayHelper::getValue($slave, 'primaryKey', new Exception("Класс {$slave->formName()} не имеет атрибута primaryKey"));
		} else $link->$second_name = (string)$slave; //suppose it string field name

		$link->save();//save or update, whatever
	}

	/**
	 * Линкует в этом релейшене две модели. Модели могут быть заданы как через айдишники, так и напрямую, в виде массивов или так.
	 * @param int|int[]|string|string[]|Model|Model[] $master
	 * @param int|int[]|string|string[]|Model|Model[] $slave
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
	 * Удаляет единичную связь в этом релейшене
	 * @param Model|int|string $master
	 * @param Model|int|string $slave
	 * @throws Throwable
	 */
	public static function unlinkModel($master, $slave):void {
		if (empty($master) || empty($slave)) return;
		$link = new self();
		$first_name = ArrayHelper::getValue($link->rules(), '0.0.0', new Exception('Не удалось получить атрибут для связи'));
		$second_name = ArrayHelper::getValue($link->rules(), '0.0.1', new Exception('Не удалось получить атрибут для связи'));

		if (is_numeric($master)) {
			$master = (int)$master;
		} elseif (is_object($master)) {
			$master = ArrayHelper::getValue($master, 'primaryKey', new Exception("Класс {$master->formName()} не имеет атрибута primaryKey"));
		} else $master = (string)$master; //suppose it string field name

		if (is_numeric($slave)) {
			$slave = (int)$slave;
		} elseif (is_object($slave)) {
			$slave = ArrayHelper::getValue($slave, 'primaryKey', new Exception("Класс {$slave->formName()} не имеет атрибута primaryKey"));
		} else $slave = (string)$slave; //suppose it string field name

		self::deleteAll([$first_name => $master, $second_name => $slave]);
	}

	/**
	 * Удаляет связь между моделями в этом релейшене
	 * @param int|int[]|string|string[]|Model|Model[] $master
	 * @param int|int[]|string|string[]|Model|Model[] $slave
	 * @throws Throwable
	 *
	 * Функция не будет работать с объектами, не имеющими атрибута/ключа id (даже если в качестве primaryKey указан другой атрибут).
	 * Такое поведение оставлено специально во избежание ошибок проектирования
	 * @see Privileges::setDropUserRights
	 *
	 * Передавать массивы строк/идентификаторов нельзя (только массив моделей)
	 */
	public static function unlinkModels($master, $slave):void {
		if (empty($master) || empty($slave)) return;
		if (is_array($master)) {
			foreach ($master as $master_item) {
				if (is_array($slave)) {
					foreach ($slave as $slave_item) {
						self::unlinkModel($master_item, $slave_item);
					}
				} else self::unlinkModel($master_item, $slave);
			}
		} else if (is_array($slave)) {
			foreach ($slave as $slave_item) {
				self::unlinkModel($master, $slave_item);
			}
		} else self::unlinkModel($master, $slave);

	}
}