<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\types;

use app\models\core\ActiveRecordExtended;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\dynamic_attributes\widgets\attribute_field\AttributeFieldWidget;
use Exception;
use kartik\range\RangeInput;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

/**
 * This is the model class for table "sys_attributes_percent".
 *
 * @property int $id
 * @property int $attribute_id ID атрибута
 * @property int $property_id ID поля
 * @property int $user_id ID пользователя
 * @property int $value Значение
 */
class AttributePropertyPercent extends ActiveRecordExtended implements AttributePropertyInterface {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_attributes_percent';
	}

	/**
	 * Конфигурация поддерживаемых типом поисковых условий.
	 * @return array
	 */
	public static function conditionConfig():array {
		return [
			['равно', static function($tableAlias, $searchValue) {
				return ['=', "$tableAlias.value", $searchValue];
			}],
			['не равно', static function($tableAlias, $searchValue) {
				return ['!=', "$tableAlias.value", $searchValue];
			}],
			['больше', static function($tableAlias, $searchValue) {
				return ['>', "$tableAlias.value", $searchValue];
			}],
			['меньше', static function($tableAlias, $searchValue) {
				return ['<', "$tableAlias.value", $searchValue];
			}],
			['меньше или равно', static function($tableAlias, $searchValue) {
				return ['<=', "$tableAlias.value", $searchValue];
			}],
			['больше или равно', static function($tableAlias, $searchValue) {
				return ['>=', "$tableAlias.value", $searchValue];
			}],
			['заполнено', static function($tableAlias, $searchValue) {
				return ['not', ["$tableAlias.value" => null]];
			}],
			['не заполнено', static function($tableAlias, $searchValue) {
				return ['is', "$tableAlias.value", new Expression('null')];
			}]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'attribute_id' => 'ID атрибута',
			'property_id' => 'ID поля',
			'user_id' => 'ID пользователя',
			'value' => 'Значение'
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['attribute_id', 'property_id', 'user_id'], 'required'],
			[['attribute_id', 'property_id', 'user_id', 'value'], 'integer'],
			[['attribute_id', 'property_id', 'user_id'], 'unique', 'targetAttribute' => ['attribute_id', 'property_id', 'user_id']]
		];
	}

	/**
	 * Вернуть из соответствующей таблицы значение поля для этого поля этого атрибута этого юзера
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @param bool $formatted
	 * @return mixed
	 */
	public static function loadValue(int $attribute_id, int $property_id, int $user_id, bool $formatted = false) {
		return Yii::$app->cache->getOrSet(static::class."GetValue{$attribute_id},{$property_id},{$user_id}", static function() use ($attribute_id, $property_id, $user_id, $formatted) {
			return (null !== $record = self::getRecord($attribute_id, $property_id, $user_id))?($formatted?Yii::$app->formatter->asPercent($record->value / 100):$record->value):null;
		});
	}

	/**
	 * Записать в соответствующую таблицу значение поля для этого поля этого атрибута этого юзера
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @param mixed $value
	 * @return bool
	 */
	public static function saveValue(int $attribute_id, int $property_id, int $user_id, $value):bool {
		if (null === $record = self::getRecord($attribute_id, $property_id, $user_id)) {
			$record = new self(compact('attribute_id', 'user_id', 'property_id', 'value'));
		} else {
			$record->setAttributes(compact('attribute_id', 'user_id', 'property_id', 'value'));
		}

		if ($record->save()) {
			Yii::$app->cache->set(static::class."GetValue{$attribute_id},{$property_id},{$user_id}", $record->value);
			return true;
		}
		return false;
	}

	/**
	 * Поиск соответствующей записи по подходящим параметрам
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @return self|ActiveRecord|null
	 */
	public static function getRecord(int $attribute_id, int $property_id, int $user_id):?self {
		return self::find()->where(compact('attribute_id', 'property_id', 'user_id'))->one();
	}

	/**
	 * Функция отдаёт форму поля для редактирования значения свойства
	 * @param ActiveForm $form
	 * @param DynamicAttributeProperty $property
	 * @return ActiveField
	 * @throws Exception
	 */
	public static function editField(ActiveForm $form, DynamicAttributeProperty $property):ActiveField {
		return $form->field($property, (string)$property->id)->widget(RangeInput::class, [
			'html5Options' => [
				'min' => 0,
				'max' => 100
			],
			'html5Container' => [
				'style' => 'width:50%'
			],
			'addon' => [
				'append' => [
					'content' => '%'
				],
				'prepend' => [
					'content' => '<span class="text-danger">0%</span>'
				],
				'preCaption' => '<span class="input-group-addon"><span class="text-success">100%</span></span>'
			],
			'options' => [
				'placeholder' => 'Укажите значение'
			]
		])->label(false);
	}

	/**
	 * Рендер поля просмотра значения свойства
	 * @param array $config Опциональные параметры виджета/поля
	 * @return string
	 * @throws Exception
	 */
	public static function viewField(array $config = []):string {
		return AttributeFieldWidget::widget($config);
	}

	/**
	 * @inheritDoc
	 */
	public static function getAverageValue(array $models):?int {
		return null;
	}

	/**
	 * @return int
	 */
	public function getValue():int {
		return $this->value;
	}

	/**
	 * @param int $value
	 */
	public function setValue($value):void {
		$this->value = $value;
	}
}
