<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\types;

use app\modules\dynamic_attributes\models\DynamicAttributePropertyAggregation;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use Throwable;
use Yii;
use yii\db\Expression;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

/**
 * This is the model class for table "sys_attributes_integer".
 *
 * @property int $id
 * @property int $attribute_id ID атрибута
 * @property int $property_id ID поля
 * @property int $user_id ID пользователя
 * @property int $value Значение
 */
class AttributePropertyInteger extends AttributeProperty {

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
	public static function tableName():string {
		return 'sys_attributes_integer';
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
	 */
	public static function editField(ActiveForm $form, DynamicAttributeProperty $property):ActiveField {
		return $form->field($property, (string)$property->id)->textInput(['type' => 'number'])->label(false);
	}

	/**
	 * Конфигурация поддерживаемых типом агрегаторов
	 * @return array
	 * @todo: возвращать поддерживаемые агрегатором параметры (отброс пустых как минимум)
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
			DynamicAttributePropertyAggregation::AGGREGATION_SUM,
			DynamicAttributePropertyAggregation::AGGREGATION_MEDIAN
		];
	}

	/**
	 * Применяет агрегатор к набору значений атрибутов
	 * @param self[] $models -- набор значений атрибутов
	 * @param int $aggregation -- выбранный агрегатор
	 * @param bool $dropNullValues -- true -- отфильтровать пустые значения из набора
	 * @return DynamicAttributePropertyAggregation -- результат агрегации в модели
	 * @throws Throwable
	 */
	public static function applyAggregation(array $models, int $aggregation, bool $dropNullValues = false):?DynamicAttributePropertyAggregation {
		switch ($aggregation) {
			case DynamicAttributePropertyAggregation::AGGREGATION_AVG:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_INTEGER,
					'value' => DynamicAttributePropertyAggregation::AggregateIntAvg($models, $dropNullValues)
				]);
			break;
			case DynamicAttributePropertyAggregation::AGGREGATION_AVG_TRUNC:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_INTEGER,
					'value' => DynamicAttributePropertyAggregation::AggregateIntAvgTrunc($models, $dropNullValues)
				]);
			break;
			case DynamicAttributePropertyAggregation::AGGREGATION_HARMONIC:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_INTEGER,
					'value' => DynamicAttributePropertyAggregation::AggregateIntHarmonic($models)
				]);
			break;
			case DynamicAttributePropertyAggregation::AGGREGATION_MODA:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_INTEGER,
					'value' => DynamicAttributePropertyAggregation::AggregateIntModa($models, $dropNullValues)
				]);
			break;
			case DynamicAttributePropertyAggregation::AGGREGATION_COUNT:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_INTEGER,
					'value' => DynamicAttributePropertyAggregation::AggregateIntCount($models, $dropNullValues)
				]);
			break;
			case DynamicAttributePropertyAggregation::AGGREGATION_MIN:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_INTEGER,
					'value' => DynamicAttributePropertyAggregation::AggregateIntMin($models, $dropNullValues)
				]);
			break;
			case DynamicAttributePropertyAggregation::AGGREGATION_MAX:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_INTEGER,
					'value' => DynamicAttributePropertyAggregation::AggregateIntMax($models, $dropNullValues)
				]);
			break;
			case DynamicAttributePropertyAggregation::AGGREGATION_SUM:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_INTEGER,
					'value' => DynamicAttributePropertyAggregation::AggregateIntSum($models, $dropNullValues)
				]);
			break;
			case DynamicAttributePropertyAggregation::AGGREGATION_MEDIAN:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_INTEGER,
					'value' => DynamicAttributePropertyAggregation::AggregateIntMedian($models, $dropNullValues)
				]);
			break;
			default:
				return DynamicAttributePropertyAggregation::AGGREGATION_UNSUPPORTED;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public static function format(mixed $value) {
		return Yii::$app->formatter->asInteger($value);
	}
}
