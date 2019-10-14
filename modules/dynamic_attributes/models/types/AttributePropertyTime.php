<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\types;

use app\modules\dynamic_attributes\models\DynamicAttributePropertyAggregation;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use Exception;
use kartik\time\TimePicker;
use Yii;
use yii\db\Expression;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

/**
 * This is the model class for table "sys_attributes_time".
 *
 * @property int $id
 * @property int $attribute_id ID атрибута
 * @property int $property_id ID поля
 * @property int $user_id ID пользователя
 * @property string $value Значение
 */
class AttributePropertyTime extends AttributeProperty {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_attributes_time';
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
			['раньше', static function($tableAlias, $searchValue) {
				return ['<', "$tableAlias.value", $searchValue];
			}],
			['позже', static function($tableAlias, $searchValue) {
				return ['>', "$tableAlias.value", $searchValue];
			}],
			['раньше или равно', static function($tableAlias, $searchValue) {
				return ['<=', "$tableAlias.value", $searchValue];
			}],
			['позже или равно', static function($tableAlias, $searchValue) {
				return ['<=', "$tableAlias.value", $searchValue];
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
	 * Функция отдаёт форму поля для редактирования значения свойства
	 * @param ActiveForm $form
	 * @param DynamicAttributeProperty $property
	 * @return ActiveField
	 * @throws Exception
	 */
	public static function editField(ActiveForm $form, DynamicAttributeProperty $property):ActiveField {
		return $form->field($property, (string)$property->id)->widget(TimePicker::class, [
			'pluginOptions' => [
				'showSeconds' => true,
				'showMeridian' => false,
				'minuteStep' => 1,
				'secondStep' => 5,
				'defaultTime' => false
			],
			'options' => [
				'placeholder' => 'Укажите время'
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
			DynamicAttributePropertyAggregation::AGGREGATION_MAX,
			DynamicAttributePropertyAggregation::AGGREGATION_SUM
		];
	}

	/**
	 * Применяет агрегатор к набору значений атрибутов
	 * @param self[] $models -- набор значений атрибутов
	 * @param int $aggregation -- выбранный агрегатор
	 * @param bool $dropNullValues -- true -- отфильтровать пустые значения из набора
	 * @return DynamicAttributePropertyAggregation -- результат агрегации в модели
	 */
	public static function applyAggregation(array $models, int $aggregation, bool $dropNullValues = false):?DynamicAttributePropertyAggregation {
		return DynamicAttributePropertyAggregation::AGGREGATION_UNSUPPORTED;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function format($value) {
		return Yii::$app->formatter->asTime($value);
	}
}
