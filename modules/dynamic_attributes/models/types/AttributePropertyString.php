<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\types;

use app\modules\dynamic_attributes\models\DynamicAttributePropertyAggregation;
use app\components\pozitronik\helpers\ArrayHelper;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use yii\db\Expression;

/**
 * This is the model class for table "sys_attributes_string".
 *
 * @property int $id
 * @property int $attribute_id ID атрибута
 * @property int $property_id ID поля
 * @property int $user_id ID пользователя
 * @property string $value Значение
 */
class AttributePropertyString extends AttributeProperty {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_attributes_string';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['attribute_id', 'property_id', 'user_id'], 'required'],
			[['attribute_id', 'property_id', 'user_id'], 'integer'],
			[['value'], 'string', 'max' => 255],
			[['attribute_id', 'property_id', 'user_id'], 'unique', 'targetAttribute' => ['attribute_id', 'property_id', 'user_id']]
		];
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
			['начинается с', static function($tableAlias, $searchValue) {
				return ['like', "$tableAlias.value", "%$searchValue", false];
			}],
			['содержит', static function($tableAlias, $searchValue) {
				return ['like', "$tableAlias.value", "%$searchValue%", false];
			}],
			['не содержит', static function($tableAlias, $searchValue) {
				return ['not like', "$tableAlias.value", "%$searchValue", false];
			}],
			['заполнено', static function($tableAlias, $searchValue) {
				return ['not', ["$tableAlias.value" => null]];
			}],
			['не заполнено', static function($tableAlias, $searchValue) {
				return ['is', "$tableAlias.value", new Expression('null')];//Внимание: пустые строки не равны null!
			}]
		];
	}

	/**
	 * Конфигурация поддерживаемых типом агрегаторов
	 * @return array
	 */
	public static function aggregationConfig():array {
		return [
			DynamicAttributePropertyAggregation::AGGREGATION_MODA,
			DynamicAttributePropertyAggregation::AGGREGATION_COUNT,
			DynamicAttributePropertyAggregation::AGGREGATION_FREQUENCY
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
		switch ($aggregation) {
			case DynamicAttributePropertyAggregation::AGGREGATION_MODA:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_STRING,
					'value' => self::getModaValue(ArrayHelper::getColumn($models, 'value'), $dropNullValues)
				]);
			case DynamicAttributePropertyAggregation::AGGREGATION_FREQUENCY:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_DICTIONARY,
					'value' => DynamicAttributePropertyAggregation::FrequencyDistribution(ArrayHelper::getColumn($models, 'value'), $dropNullValues)
				]);
			case DynamicAttributePropertyAggregation::AGGREGATION_COUNT:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_INTEGER,
					'value' => DynamicAttributePropertyAggregation::AggregateIntCount($models, $dropNullValues)
				]);
			default:
				return DynamicAttributePropertyAggregation::AGGREGATION_UNSUPPORTED;
		}
	}

	/**
	 * @param array $values
	 * @param bool $dropNullValues
	 * @return string|null
	 */
	public static function getModaValue(array $values, bool $dropNullValues = true):?string {
		$values = $dropNullValues?ArrayHelper::filterValues($values):$values;
		$modaArray = array_count_values(array_map(static function($value) {
			return null === $value?'':(string)$value;
		}, $values));
		if ($dropNullValues) unset ($modaArray['']);

		$maxValue = count($modaArray)?max($modaArray):null;
		return (string)array_search($maxValue, $modaArray);//наиболее часто встречаемое значение
	}

}
