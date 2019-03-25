<?php
declare(strict_types = 1);

namespace app\models\core\traits;

use app\helpers\ArrayHelper;
use app\models\core\SysExceptions;
use app\modules\privileges\models\AccessMethods;
use app\modules\privileges\models\UserAccess;
use app\modules\import\models\fos\ImportException;
use app\widgets\alert\AlertModel;
use RuntimeException;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;
use Throwable;

/**
 * Trait ARExtended
 * Расширения модели ActiveRecord
 *
 */
trait ARExtended {

	/**
	 * Обёртка для быстрого поиска моделей с опциональным выбросом логируемого исключения
	 * Упрощает проверку поиска моделей
	 * @example Users::findModel($id, new NotFoundException('Пользователь не найден'))
	 *
	 * @example if (null !== $user = Users::findModel($id)) return $user
	 * @param mixed $id Поисковое условие (предпочтительно primaryKey, но не ограничиваемся им)
	 * @param null|Throwable $throw - Если передано исключение, оно выбросится в случае ненахождения модели
	 * @return null|self
	 * @throws Throwable
	 */
	public static function findModel($id, ?Throwable $throw = null):?self {
		if (null !== ($model = self::findOne($id))) return $model;
		if (null !== $throw) SysExceptions::log($throw, true, true);
		return null;
	}

	/**
	 * Ищет по указанному условию, возвращая указанный атрибут модели или $default, если модель не найдена
	 * @param mixed $condition Поисковое условие
	 * @param string|null $attribute Возвращаемый атрибут (если не задан, то вернётся первичный ключ)
	 * @param null|mixed $default
	 * @return mixed
	 * @throws InvalidConfigException
	 */
	public static function findModelAttribute($condition, ?string $attribute = null, $default = null) {
		if (null === $model = self::findOne($condition)) return $default;

		if (null === $attribute) {
			$primaryKeys = self::primaryKey();
			if (!isset($primaryKeys[0])) throw new InvalidConfigException('"'.static::class.'" must have a primary key.');

			$attribute = $primaryKeys[0];
		}
		return $model->$attribute;
	}

	/**
	 * По итерируемому списку ключей вернёт список подходящих моделей
	 * @param int[] $keys Итерируемый список ключей
	 * @return self[]
	 * @throws Throwable
	 */
	public static function findModels(array $keys):array {
		$result = [];
		foreach ($keys as $key) {
			if (null !== $model = self::findModel($key)) $result[] = $model;
		}
		return $result;
	}

	/**
	 * Возвращает существующую запись в ActiveRecord-модели, найденную по условию, если же такой записи нет - возвращает новую модель
	 * @param array|string $searchCondition
	 * @return ActiveRecord|self
	 * @todo: не работает с наследуемыми моделями, вроде референсов, разобраться.
	 */
	public static function getInstance($searchCondition):self {
		/** @noinspection PhpUndefinedMethodInspection */
		$instance = self::find()->where($searchCondition)->one();
		return $instance??new self;
	}

	/**
	 * Первый параметр пока что специально принудительно указываю массивом, это позволяет не накосячить при задании параметров. Потом возможно будет убрать
	 * @param array $searchCondition
	 * @param null|array $fields
	 * @param bool $ignoreEmptyCondition Игнорировать пустое поисковое значение
	 * @return ActiveRecord|self|null
	 * @throws ImportException
	 */
	public static function addInstance(array $searchCondition, ?array $fields = null, bool $ignoreEmptyCondition = true):?self {//todo: add UPDATE flag for updating fields even for existed rows
		if ($ignoreEmptyCondition && (empty($searchCondition) || (is_array($searchCondition) && empty(reset($searchCondition))))) return null;

		/** @var ActiveRecord $instance */
		if (null === $instance = self::findOne($searchCondition)) {
			$fields = $fields??$searchCondition;
			/** @noinspection PhpMethodParametersCountMismatchInspection */
			$instance = new self($fields);
			if (!$instance->save()) {
				throw new ImportException($instance, $instance->errors);
			}
			return $instance;
		}
		return $instance;
	}

	/**
	 * Обратный аналог oldAttributes: после изменения AR возвращает массив только изменённых атрибутов
	 * @param array $changedAttributes Массив старых изменённых аттрибутов
	 * @return array
	 */
	public function newAttributes(array $changedAttributes):array {
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
	public function changedAttributes(array $changedAttributes):array {
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
	 * @throws Throwable
	 */
	public function identifyChangedAttributes():array {
		$changedAttributes = [];
		/** @noinspection ForeachSourceInspection */
		foreach ($this->attributes as $name => $value) {
			/** @noinspection TypeUnsafeComparisonInspection */
			if (ArrayHelper::getValue($this, "oldAttributes.$name") != $value) $changedAttributes[$name] = $value;//Нельзя использовать строгое сравнение из-за преобразований БД
		}
		return $changedAttributes;
	}

	/**
	 * Работает аналогично saveAttribute, но сразу сохраняет данные
	 * Отличается от updateAttribute тем, что триггерит onAfterSave
	 * @param string $name
	 * @param mixed $value
	 */
	public function setAndSaveAttribute(string $name, $value):void {
		$this->setAttribute($name, $value);
		$this->save();
	}

	/**
	 * Работает аналогично saveAttributes, но сразу сохраняет данные
	 * Отличается от updateAttributes тем, что триггерит onAfterSave
	 * @param null|array $values
	 */
	public function setAndSaveAttributes(?array $values):void {
		$this->setAttributes($values, false);
		$this->save();
	}

	/**
	 * Универсальная функция удаления любой модели
	 */
	public function safeDelete():void {
		/** @var Model $this */
		if (!UserAccess::canAccess($this, AccessMethods::delete)) {
			AlertModel::AccessNotify();
			return;
		}

		if ($this->hasAttribute('deleted')) {
			/** @noinspection PhpUndefinedFieldInspection */
			$this->setAndSaveAttribute('deleted', !$this->deleted);
		} else {
			$this->delete();
		}
	}

	/**
	 * Грузим объект из массива без учёта формы
	 * @param null|array $arrayData
	 * @return boolean
	 */
	public function loadArray(?array $arrayData):bool {
		return $this->load($arrayData, '');
	}

	/**
	 * @param string $property
	 * @return string
	 */
	public function asJSON(string $property):string {
		if (!$this->hasAttribute($property)) throw new RuntimeException("Field $property not exists in the table ".$this::tableName());
		return json_encode($this->$property, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
	}

}