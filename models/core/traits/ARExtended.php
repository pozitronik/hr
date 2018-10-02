<?php
declare(strict_types=1);

namespace app\models\core\traits;

use app\models\core\SysExceptions;
use Iterator;
use RuntimeException;
use yii\db\ActiveRecord;
use ReflectionClass;
use Throwable;

/**
 * Trait ARExtended
 * Расширения модели ActiveRecord
 *
 * @property-read string $classNameShort
 */
trait ARExtended {

	/**
	 * @return string|null
	 * @throws Throwable
	 */
	public function getClassNameShort(): ?string {
		try {
			return (new ReflectionClass($this))->getShortName();
		} catch (Throwable $t) {
			SysExceptions::log($t, $t);
		}
		return null;
	}

	/**
	 * Обёртка для быстрого поиска моделей
	 * Упрощает проверку поиска моделей по индексу. Пример:
	 * if ($user = Users::findModel($id) {
	 *        //$user инициализирован
	 * } else throw new Exception('Not found')
	 * @param integer $id
	 * @param bool|Throwable $throw - Если передано исключение, оно выбросится в случае ненахождения модели
	 * @return bool|self
	 * @throws Throwable
	 */
	public static function findModel($id, $throw = null) {
		/** @noinspection PhpIncompatibleReturnTypeInspection *///Давим некорректно отрабатывающую инспекцию (не учитывает два возможных типа возвращаемых значений)
		if (null !== ($model = self::findOne($id))) return $model;
		if (null !== $throw) SysExceptions::log($throw, $throw, true);
		return false;
	}

	/**
	 * По итерируемому списку ключей вернёт список подходящих моделей
	 * @param Iterator|array $keys Итерируемый список ключей
	 * @return self[]
	 * @throws Throwable
	 */
	public static function findModels($keys): array {
		$result = [];
		foreach ($keys as $key) {
			$model = self::findModel($key);
			if ($model) $result[] = $model;
		}
		return $result;
	}

	/**
	 * Возвращает существующую запись в ActiveRecord-модели, найденную по условию, если же такой записи нет - возвращает новую модель
	 * @param array $searchCondition
	 * @return array|null|ActiveRecord|self
	 */
	public static function getInstance($searchCondition) {
		/** @noinspection PhpUndefinedMethodInspection */
		$instance = self::find()->where($searchCondition)->one();
		return $instance?:new self;
	}

	/**
	 * Обратный аналог oldAttributes: после изменения AR возвращает массив только изменённых атрибутов
	 * @param array $changedAttributes Массив старых изменённых аттрибутов
	 * @return array
	 */
	public function newAttributes($changedAttributes): array {
		/** @var ActiveRecord $this */
		$newAttributes = [];
		$currentAttributes = $this->attributes;
		foreach ($changedAttributes as $item => $value) {
			if ($currentAttributes[$item] !== $value) $newAttributes[$item] = $currentAttributes[$item];
		}
		return $newAttributes;
	}

	/**
	 * Фикс для changedAttributes, который неправильно отдаёт список изменённых аттрибутов (туда включаются аттрибуты, по факту не менявшиеся).
	 * @param array $changedAttributes
	 * @return array
	 */
	public function changedAttributes($changedAttributes): array {
		/** @var ActiveRecord $this */
		$updatedAttributes = [];
		$currentAttributes = $this->attributes;
		foreach ($changedAttributes as $item => $value) {
			if ($currentAttributes[$item] !== $value) $updatedAttributes[$item] = $value;
		}
		return $updatedAttributes;
	}

	/**
	 * Вычисляет разницу между старыми и новыми аттрибутами
	 * @return array
	 */
	public function identifyChangedAttributes(): array {
		$changedAttributes = [];
		/** @noinspection ForeachSourceInspection */
		foreach ($this->attributes as $name => $value) {
			/** @noinspection TypeUnsafeComparisonInspection */
			if ($this->oldAttributes[$name] != $value) $changedAttributes[$name] = $value;//Нельзя использовать строгое сравнение из-за преобразований БД
		}
		return $changedAttributes;
	}

	/**
	 * Работает аналогично saveAttribute, но сразу сохраняет данные
	 * Отличается от updateAttribute тем, что триггерит onAfterSave
	 * @param string $name
	 * @param mixed $value
	 */
	public function setAndSaveAttribute($name, $value): void {
		$this->setAttribute($name, $value);
		$this->save();
	}

	/**
	 * Работает аналогично saveAttributes, но сразу сохраняет данные
	 * Отличается от updateAttributes тем, что триггерит onAfterSave
	 * @param array $values
	 */
	public function setAndSaveAttributes($values): void {
		$this->setAttributes($values, false);
		$this->save();
	}

	/**
	 * Универсальная функция удаления любой лайткабовской модели
	 */
	public function safeDelete(): void {
		if ($this->hasAttribute('deleted')) {
			$this->setAndSaveAttribute('deleted', !$this->deleted);
		} else {
			$this->delete();
		}
	}

	/**
	 * Грузим объект из массива без учёта формы
	 * @param array $arrayData
	 * @return boolean
	 */
	public function loadArray($arrayData): bool {
		return $this->load($arrayData, '');
	}

	/**
	 * @param $property
	 * @return string
	 */
	public function asJSON($property): string {
		if (!$this->hasAttribute($property)) throw new RuntimeException("Field $property not exists in the table ".$this::tableName());
		return json_encode($this->$property, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
	}

}