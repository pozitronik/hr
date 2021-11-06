<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\types;

use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\dynamic_attributes\models\DynamicAttributePropertyAggregation;
use Exception;
use kartik\range\RangeInput;
use Yii;
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
class AttributePropertyPercent extends AttributeProperty {
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
	 * Конфигурация поддерживаемых типом агрегаторов
	 * @return array
	 */
	public static function aggregationConfig():array {
		return [
			DynamicAttributePropertyAggregation::AGGREGATION_AVG,
			DynamicAttributePropertyAggregation::AGGREGATION_HARMONIC,
			DynamicAttributePropertyAggregation::AGGREGATION_MODA,
			DynamicAttributePropertyAggregation::AGGREGATION_AVG_TRUNC,
			DynamicAttributePropertyAggregation::AGGREGATION_COUNT,
			DynamicAttributePropertyAggregation::AGGREGATION_MIN,
			DynamicAttributePropertyAggregation::AGGREGATION_MAX
		];
	}

	/**
	 * Применяет агрегатор к набору значений атрибутов
	 * @param self[] $models -- набор значений атрибутов
	 * @param int $aggregation -- выбранный агрегатор
	 * @param bool $dropNullValues -- true -- отфильтровать пустые значения из набора
	 * @return DynamicAttributePropertyAggregation|null -- результат агрегации в модели
	 */
	public static function applyAggregation(array $models, int $aggregation, bool $dropNullValues = false):?DynamicAttributePropertyAggregation {
		return DynamicAttributePropertyAggregation::AGGREGATION_UNSUPPORTED;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function format(mixed $value) {
		return Yii::$app->formatter->asPercent($value);
	}
}
