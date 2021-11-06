<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\types;

use app\modules\dynamic_attributes\models\DynamicAttributePropertyAggregation;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use Exception;
use kartik\switchinput\SwitchInput;
use app\components\pozitronik\helpers\ArrayHelper;
use Yii;
use yii\db\Expression;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

/**
 * This is the model class for table "sys_attributes_boolean".
 *
 * @property int $id
 * @property int $attribute_id ID атрибута
 * @property int $property_id ID поля
 * @property int $user_id ID пользователя
 * @property int $value Значение
 */
class AttributePropertyBoolean extends AttributeProperty {

	/**
	 * Конфигурация поддерживаемых типом поисковых условий.
	 * @return array
	 */
	public static function conditionConfig():array {
		return [
			['да', static function($tableAlias, $searchValue) {
				return ['=', "$tableAlias.value", true];
			}],
			['нет', static function($tableAlias, $searchValue) {
				return ['=', "$tableAlias.value", false];
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
		return 'sys_attributes_boolean';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['attribute_id', 'property_id', 'user_id'], 'required'],
			[['attribute_id', 'property_id', 'user_id'], 'integer'],
			[['value'], 'boolean'],
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
		return $form->field($property, (string)$property->id)->widget(SwitchInput::class)->label(false);
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
					'type' => DynamicAttributeProperty::PROPERTY_BOOLEAN,
					'value' => self::getModaValue(ArrayHelper::getColumn($models, 'value'), $dropNullValues)
				]);
			case DynamicAttributePropertyAggregation::AGGREGATION_FREQUENCY:
				$values = DynamicAttributePropertyAggregation::FrequencyDistribution(ArrayHelper::getColumn($models, 'value'), false);
				foreach ($values as $key => &$value) {
					$value['value'] = self::format($value['value']);
				}
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_DICTIONARY,
					'value' => $values
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
		$values = $dropNullValues?ArrayHelper::filterValues($values, ['', null]):$values;
		$modaArray = array_count_values(array_map(static function($value) {
			return null === $value?'':(string)$value;
		}, $values));
		if ($dropNullValues) unset ($modaArray['']);

		$maxValue = count($modaArray)?max($modaArray):null;
		return (string)array_search($maxValue, $modaArray);//наиболее часто встречаемое значение
	}

	/**
	 * {@inheritDoc}
	 */
	public static function format(mixed $value) {
		return Yii::$app->formatter->asBoolean($value);
	}
}
