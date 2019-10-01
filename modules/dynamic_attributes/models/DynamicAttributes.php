<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models;
/**
 * Глобально нужно переписать хранилище компетенций в некоторую абстрактную сущность a-la DynamicStorage, а компетенции сделать надстройкой над ней.
 * Это позволит абстрагироваться от внутренних методов работы модели, и использовать её в любых местах где нужно динамическое атрибутирование.
 * Саму сущность потребуется утащить в компонент.
 */

use pozitronik\helpers\ArrayHelper;
use app\helpers\DateHelper;
use app\models\core\ActiveRecordExtended;
use app\models\core\core_module\PluginTrait;
use app\models\core\SysExceptions;
use app\models\relations\RelUsersAttributes;
use app\models\user\CurrentUser;
use app\modules\users\models\Users;
use app\widgets\alert\AlertModel;
use RuntimeException;
use Throwable;
use Yii;
use yii\db\ActiveQuery;

/**
 * Атрибут - сугубо динамическая штука, состоящая из произвольного набора свойств.
 * Хранить структуру будем в JSON-поле таблицы. Редактор атрибутов редактирует эту структуру: набор, порядок и тип свойств, а также их валидацию.
 * @property int $id
 * @property string $name Название атрибута
 * @property int $category Категория
 * @property int $daddy Создатель
 * @property string $create_date Дата создания
 * @property array $structure Структура свойств
 * @property integer $access
 * @property int $deleted Флаг удаления
 *
 * @property-read DynamicAttributeProperty[] $properties
 *
 * @property RelUsersAttributes[]|ActiveQuery $relUsersAttributes Релейшен к таблице связей с атрибутами
 * @property Users|ActiveQuery $relUsers Пользователи с этим атрибутом
 * @property-read string $categoryName Строковое имя категории
 * @property-read bool $hasIntegerProperties
 * @property int $userProperties
 * @property-read int $usersCount
 */
class DynamicAttributes extends ActiveRecordExtended {
	use PluginTrait;

	private $_virtualPropertyValues = [];//Хранение виртуальных значений атрибутов

	public const CATEGORIES = [/*Ну хер знает*/
		0 => 'Общая категория',
		1 => 'Обучение',
		2 => 'Навык',
		3 => 'Область экспертизы'
	];

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_attributes';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['category', 'daddy', 'access'], 'integer'],
			[['deleted'], 'boolean'],
			[['create_date', 'structure'], 'safe'],
			[['name'], 'string', 'max' => 255],
			[['daddy'], 'default', 'value' => CurrentUser::Id()],
			[['create_date'], 'default', 'value' => DateHelper::lcDate()],
			[['structure'], 'default', 'value' => []]

		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название атрибута',
			'category' => 'Категория',
			'categoryName' => 'Категория',
			'daddy' => 'Создатель',
			'create_date' => 'Дата создания',
			'structure' => 'Структура',
			'access' => 'Доступ',
			'deleted' => 'Флаг удаления'
		];
	}

	/**
	 * @param int $property_id
	 * @return bool
	 */
	public function deleteProperty(int $property_id):bool {
		$t = $this->structure;
		if (isset($t[$property_id])) {
			unset($t[$property_id]);
			$this->setAndSaveAttribute('structure', $t);
			return true;
		}
		return false;
	}

	/**
	 * @param DynamicAttributeProperty $attribute
	 * @param int|null $property_id
	 * @return int
	 */
	public function setProperty(DynamicAttributeProperty $attribute, ?int $property_id):int {
		$t = $this->structure;
		if (null === $property_id) $property_id = count($this->structure) + 1;
		$t[$property_id] = [
			'id' => $property_id,
			'name' => $attribute->name,
			'type' => $attribute->type,
			'required' => $attribute->required
		];
		$this->setAndSaveAttribute('structure', $t);
		return $property_id;
	}

	/**
	 * Ищет свойство по его имени
	 * @param string $propertyName
	 * @return DynamicAttributeProperty|null
	 * @throws Throwable
	 */
	public function getPropertyByName(string $propertyName):?DynamicAttributeProperty {
		foreach ($this->structure as $property) {
			if ($propertyName === ArrayHelper::getValue($property, 'name')) {
				return new DynamicAttributeProperty(array_merge($property, ['attributeId' => $this->id]));
			}
		}
		return null;
	}

	/**
	 * Ищет свойство по индексу
	 * @param int $id
	 * @param null $throw
	 * @return DynamicAttributeProperty|false
	 * @throws Throwable
	 */
	public function getPropertyById(int $id, $throw = null):?DynamicAttributeProperty {
		if (null !== $data = ArrayHelper::getValue($this->structure, $id)) return new DynamicAttributeProperty(array_merge($data, ['attributeId' => $this->id]));
		if (null !== $throw) SysExceptions::log($throw, true, true);
		return false;
	}

	/**
	 * Массив атрибутов пользователя
	 * @param int $user_id
	 * @return DynamicAttributeProperty[]
	 */
	public function getUserProperties(int $user_id):array {
		return Yii::$app->cache->getOrSet(static::class."GetUser{$this->id}Properties".$user_id, function() use ($user_id) {
			$result = [];
			foreach ($this->structure as $property) {
				$property = new DynamicAttributeProperty(array_merge($property, [
					'attributeId' => $this->id,
					'userId' => $user_id
				]));
				$result[] = $property;
			}
			return $result;
		});

	}

	/**
	 * @param int $user_id
	 * @param array $values
	 * @throws Throwable
	 */
	public function setUserProperties(int $user_id, array $values):void {
		foreach ($values as $key => $value) {
			$this->setUserProperty($user_id, $key, $value);
		}
		Yii::$app->cache->delete(static::class."GetUser{$this->id}Properties".$user_id);
	}

	/**
	 * Очищает значения всех свойств атрибута у пользователя
	 * @param int $user_id
	 * @throws Throwable
	 */
	public function clearUserProperties(int $user_id):void {
		foreach ($this->properties as $property) {
			$this->setUserProperty($user_id, $property->id, null);
		}
	}

	/**
	 * Устанавливает значение свойства атрибута для пользователя
	 * @param int $user_id
	 * @param int $property_id
	 * @param $property_value
	 * @throws Throwable
	 */
	public function setUserProperty(int $user_id, int $property_id, $property_value):void {
		$property = $this->getPropertyById($property_id);
		$typeClass = DynamicAttributeProperty::getTypeClass($property->type);
		try {
			if ($typeClass::saveValue($this->id, $property_id, $user_id, $property_value)) {
				AlertModel::SuccessNotify();
			} else {
				AlertModel::ErrorsNotify([$typeClass => 'not saved!']);
			}
		} catch (Throwable $t) {
			SysExceptions::log($t);
			SysExceptions::log(new RuntimeException("Attribute property type {$property->type} not implemented or not configured."), false, true);
			AlertModel::ErrorsNotify([$typeClass => "Attribute property type {$property->type} not implemented or not configured."]);
		}
		Yii::$app->cache->delete(static::class."GetUser{$this->id}Properties".$user_id);
	}

	/**
	 * Устанавливает свойству виртуальное значение атрибута, которое не привязано ни к чему, и существует только вместе с самим объектом динамического атрибута
	 * @param int $property_id
	 * @param $property_value
	 */
	public function setVirtualProperty(int $property_id, $property_value):void {
		ArrayHelper::setValue($this->_virtualPropertyValues, $property_id, $property_value);
	}

	/**
	 * @param int $property_id
	 * @return mixed -- значение запрошенного виртуального свойства
	 * @throws Throwable
	 */
	public function getVirtualProperty(int $property_id) {
		$property = $this->getPropertyById($property_id);
		$property->value = ArrayHelper::getValue($this->_virtualPropertyValues, $property_id);
		return $property;
	}

	/**
	 * @return array
	 */
	public function getVirtualProperties():array {
		$virtualProperties = [];
		foreach ($this->properties as $property) {
			$virtualProperties[] = $this->getVirtualProperty($property->id);
		}
		return $virtualProperties;
	}

	/**
	 * @return Users|ActiveQuery
	 */
	public function getRelUsers() {
		return $this->hasMany(Users::class, ['id' => 'user_id'])->via('relUsersAttributes');
	}

	/**
	 * @return RelUsersAttributes[]|ActiveQuery
	 */
	public function getRelUsersAttributes() {
		return $this->hasMany(RelUsersAttributes::class, ['attribute_id' => 'id']);
	}

	/**
	 * @return string
	 * @throws Throwable
	 */
	public function getCategoryName():string {
		return ArrayHelper::getValue(self::CATEGORIES, $this->category);
	}

	/**
	 * @return DynamicAttributeProperty[]
	 */
	public function getProperties():array {
		$properties = [];
		foreach ($this->structure as $property) {
			$properties[] = new DynamicAttributeProperty(array_merge($property, ['attributeId' => $this->id]));
		}
		return $properties;
	}

	/**
	 * @return bool
	 */
	public function getHasIntegerProperties():bool {
		foreach ($this->properties as $property) {
			if (in_array($property->type, ['integer', 'percent'])) return true;//большего особо не требуется
		}
		return false;
	}

	/**
	 * @return int
	 */
	public function getUsersCount():int {
		return (int)$this->getRelUsers()->count();
	}
}
