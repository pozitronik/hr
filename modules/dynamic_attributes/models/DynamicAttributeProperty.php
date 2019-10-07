<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models;

use pozitronik\helpers\ArrayHelper;
use app\modules\dynamic_attributes\models\types\AttributePropertyBoolean;
use app\modules\dynamic_attributes\models\types\AttributePropertyDate;
use app\modules\dynamic_attributes\models\types\AttributePropertyInteger;
use app\modules\dynamic_attributes\models\types\AttributePropertyInterface;
use app\modules\dynamic_attributes\models\types\AttributePropertyPercent;
use app\modules\dynamic_attributes\models\types\AttributePropertyScore;
use app\modules\dynamic_attributes\models\types\AttributePropertyString;
use app\modules\dynamic_attributes\models\types\AttributePropertyText;
use app\modules\dynamic_attributes\models\types\AttributePropertyTime;
use app\models\core\SysExceptions;
use Exception;
use Throwable;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\UnknownPropertyException;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

/**
 * Описание структуры свойства атрибута
 * @package app\models\attributes
 *
 * @property integer $attributeId
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property boolean $required
 *
 * @property integer $userId;
 *
 * @property boolean $isNewRecord
 * @property DynamicAttributes $dynamicAttribute
 * @property mixed $value -- атрибут для обращения к виртуальному (не хранящемуся в БД) значению
 * @property-read string $categoryName
 */
class DynamicAttributeProperty extends Model {
	private $attribute_id; //Внутреннее поле для связи с атрибутом
	private $id;
	private $name = '';
	private $type = 'integer';
	private $required = false;

	private $user_id;
	private $_virtualValue;

	public const PROPERTY_INTEGER = 'integer';
	public const PROPERTY_BOOLEAN = 'boolean';
	public const PROPERTY_STRING = 'string';
	public const PROPERTY_DATE = 'date';
	public const PROPERTY_TIME = 'time';
	public const PROPERTY_PERCENT = 'percent';
	public const PROPERTY_TEXT = 'text';
	public const PROPERTY_SCORE = 'score';

	public const PROPERTY_TYPES = [
		self::PROPERTY_INTEGER => [/*Название (индекс) типа данных*/
			'label' => 'Число',/*Отображаемое в интефейсах имя*/
			'model' => AttributePropertyInteger::class,/*Имя класса, реализующего взаимоделйствие с типом данных, обязательно имплементация AttributePropertyInterface. Поле названо model, потому что на class ругается инспектор*/
		],
		self::PROPERTY_BOOLEAN => [
			'label' => 'Логический тип',
			'model' => AttributePropertyBoolean::class
		],
		self::PROPERTY_STRING => [
			'label' => 'Строка',
			'model' => AttributePropertyString::class
		],
		self::PROPERTY_DATE => [
			'label' => 'Дата',
			'model' => AttributePropertyDate::class
		],
		self::PROPERTY_TIME => [
			'label' => 'Время',
			'model' => AttributePropertyTime::class
		],
		self::PROPERTY_PERCENT => [
			'label' => 'Проценты',
			'model' => AttributePropertyPercent::class
		],
		self::PROPERTY_TEXT => [
			'label' => 'Текст',
			'model' => AttributePropertyText::class
		],
		self::PROPERTY_SCORE => [
			'label' => 'Оценка',
			'model' => AttributePropertyScore::class
		]
	];

	/**
	 * @param string $type
	 * @return AttributePropertyInterface|string
	 * @throws Throwable
	 */
	public static function getTypeClass(string $type):string {
		if (null === $value = ArrayHelper::getValue(self::PROPERTY_TYPES, "$type.model")) {
			SysExceptions::log(new InvalidConfigException("$type.model AttributePropertyInterface not set or not properly configured"), true);
		}
		return $value;
	}

	/**
	 * @inheritdoc
	 */
	public function rules():array {
		return [
			[['id'], 'integer'],
			[['id'], 'unique'],
			[['name', 'type'], 'string'],
			[['type'], 'in', 'range' => array_keys(self::PROPERTY_TYPES)],
			[['required'], 'boolean'],
			[['name', 'type', 'required'], 'required']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'id' => 'Ключ/порядок',
			'name' => 'Название',
			'type' => 'Тип',
			'required' => 'Обязательное свойство'
		];
	}

	/**
	 * @inheritdoc
	 */
	public function __get($name) {
		$getter = 'get'.$name;
		if (method_exists($this, $getter)) {
			return $this->$getter();
		}
		if (method_exists($this, 'set'.$name)) {
			throw new InvalidCallException('Getting write-only property: '.get_class($this).'::'.$name);
		}
		if ((int)$name === $this->id) {/*Хачим геттер метода для совместимости с ActiveForm::field*/
			return $this->getValue();
		}

		throw new UnknownPropertyException('Getting unknown property: '.get_class($this).'::'.$name);
	}

	/**
	 * @return string
	 */
	public function getName():string {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name):void {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getType():string {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType(string $type):void {
		$this->type = $type;
	}

	/**
	 * @return bool
	 */
	public function getRequired():bool {
		return $this->required;
	}

	/**
	 * @param bool $required
	 */
	public function setRequired(bool $required):void {
		$this->required = $required;
	}

	/**
	 * @return bool
	 */
	public function getIsNewRecord():bool {
		return null === $this->id;
	}

	/**
	 * @return integer
	 */
	public function getId():int {
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getAttributeId():int {
		return $this->attribute_id;
	}

	/**
	 * @param int $id
	 */
	public function setId(int $id):void {
		$this->id = $id;
	}

	/**
	 * @param int $attribute_id
	 */
	public function setAttributeId(int $attribute_id):void {
		$this->attribute_id = $attribute_id;
	}

	/**
	 * Вернёт значение атрибута свойства для указанного пользователя
	 * Если пользователь не задан (нет привязки к БД), считаем, что атрибут виртуальный,
	 * @param bool $formatted
	 * @return mixed
	 * @throws Throwable
	 */
	public function getValue(bool $formatted = false) {
		return null === $this->user_id?$this->_virtualValue:self::getTypeClass($this->type)::loadValue($this->attribute_id, $this->id, $this->user_id, $formatted);
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value):void {
		$this->_virtualValue = $value;
	}

	/**
	 * @return int
	 */
	public function getUserId():int {
		return $this->user_id;
	}

	/**
	 * @param int $user_id
	 */
	public function setUserId(int $user_id):void {
		$this->user_id = $user_id;
	}

	/**
	 * @return DynamicAttributes
	 * @throws Throwable
	 */
	public function getDynamicAttribute():DynamicAttributes {
		return DynamicAttributes::findModel($this->attribute_id);
	}

	/**
	 * Функция отдаёт поле/виджет просмотра, ассоциированный с этим свойством
	 * @param array $config
	 * @return string
	 * @throws Exception
	 * @throws Throwable
	 */
	public function viewField(array $config):string {
		$config['model'] = $this;
		return self::getTypeClass($this->type)::viewField($config);
	}

	/**
	 * Функция отдаёт форму поля для редактирования значения свойства
	 * @param ActiveForm $form
	 * @return ActiveField
	 * @throws Throwable
	 */
	public function editField(ActiveForm $form):ActiveField {
		return self::getTypeClass($this->type)::editField($form, $this);
	}

	/**
	 * @return string
	 * @throws Throwable
	 */
	public function getCategoryName():string {
		return ArrayHelper::getValue(ArrayHelper::getColumn(self::PROPERTY_TYPES, 'label'), $this->type);
	}

	/**
	 * prototype fixme
	 * @param array $models
	 * @return mixed
	 * @throws Throwable
	 */
	public function getAverageValue(array $models) {
		return self::getTypeClass($this->getType())::getAverageValue($models);
	}

}