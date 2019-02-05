<?php
declare(strict_types = 1);

namespace app\models\dynamic_attributes;

use app\helpers\ArrayHelper;
use app\models\dynamic_attributes\types\AttributePropertyBoolean;
use app\models\dynamic_attributes\types\AttributePropertyDate;
use app\models\dynamic_attributes\types\AttributePropertyInteger;
use app\models\dynamic_attributes\types\AttributePropertyInterface;
use app\models\dynamic_attributes\types\AttributePropertyPercent;
use app\models\dynamic_attributes\types\AttributePropertyScore;
use app\models\dynamic_attributes\types\AttributePropertyString;
use app\models\dynamic_attributes\types\AttributePropertyText;
use app\models\dynamic_attributes\types\AttributePropertyTime;
use app\models\core\SysExceptions;
use app\modules\dynamic_attributes\widgets\attribute_field\AttributeFieldWidget;
use app\modules\dynamic_attributes\widgets\attribute_field_score\ScoreWidget;
use Exception;
use kartik\date\DatePicker;
use kartik\range\RangeInput;
use kartik\switchinput\SwitchInput;
use kartik\time\TimePicker;
use RuntimeException;
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
 * @property boolean isNewRecord
 * @property mixed $value
 * @property DynamicAttributes $dynamicAttribute
 * @property-read string $categoryName
 */
class DynamicAttributeProperty extends Model {
	private $attribute_id; //Внутреннее поле для связи с атрибутом
	private $id;
	private $name = '';
	private $type = 'integer';
	private $required = false;

	private $user_id;

	public const PROPERTY_TYPES = [
		'integer' => [/*Название (индекс) типа данных*/
			'label' => 'Число',/*Отображаемое в интефейсах имя*/
			'model' => AttributePropertyInteger::class,/*Имя класса, реализующего взаимоделйствие с типом данных, обязательно имплементация AttributePropertyInterface. Поле названо model, потому что на class ругается инспектор*/
		],
		'boolean' => [
			'label' => 'Логический тип',
			'model' => AttributePropertyBoolean::class
		],
		'string' => [
			'label' => 'Строка',
			'model' => AttributePropertyString::class
		],
		'date' => [
			'label' => 'Дата',
			'model' => AttributePropertyDate::class
		],
		'time' => [
			'label' => 'Время',
			'model' => AttributePropertyTime::class
		],
		'percent' => [
			'label' => 'Проценты',
			'model' => AttributePropertyPercent::class
		],
		'text' => [
			'label' => 'Текст',
			'model' => AttributePropertyText::class
		],
		'score' => [
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
			return $this->value;
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
	 * @return mixed
	 * @throws Throwable
	 */
	public function getValue() {
		return self::getTypeClass($this->type)::getValue($this->attribute_id, $this->id, $this->user_id);
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value):void {

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
	 * Функция отдаёт виджет, ассоциированный с этим свойством
	 * @param array $config
	 * @return string
	 * @throws Exception
	 */
	public function widget(array $config):string {//todo: определяться через self::PROPERTY_TYPES
		$config['model'] = $this;
		switch ($this->type) {
			case 'score':
				return ScoreWidget::widget($config);
			break;
			default:
				return AttributeFieldWidget::widget($config);
			break;
		}
	}

	/**
	 * Функция отдаёт форму поля для редактирования значения свойства
	 * @param ActiveForm $form
	 * @return ActiveField
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

}