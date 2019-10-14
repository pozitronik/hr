<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\types;

use app\modules\dynamic_attributes\models\DynamicAttributePropertyAggregation;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use Exception;
use kartik\date\DatePicker;
use Yii;
use yii\db\Expression;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

/**
 * This is the model class for table "sys_attributes_date".
 *
 * @property int $id
 * @property int $attribute_id ID атрибута
 * @property int $property_id ID поля
 * @property int $user_id ID пользователя
 * @property string $value Значение
 */
class AttributePropertyDate extends AttributeProperty {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_attributes_date';
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
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['attribute_id', 'property_id', 'user_id'], 'required'],
			[['attribute_id', 'property_id', 'user_id'], 'integer'],
			[['value'], 'safe'],
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
		return $form->field($property, (string)$property->id)->widget(DatePicker::class, [
			'pluginOptions' => [
				'autoclose' => true,
				'format' => 'yyyy-mm-dd'
			],
			'options' => [
				'placeholder' => 'Укажите дату'
			]
		])->label(false);
	}

	/**
	 * Конфигурация поддерживаемых типом агрегаторов
	 * @return array
	 */
	public static function aggregationConfig():array {
		return [DynamicAttributePropertyAggregation::AGGREGATION_MODA, DynamicAttributePropertyAggregation::AGGREGATION_COUNT, DynamicAttributePropertyAggregation::AGGREGATION_AVG];
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
		return Yii::$app->formatter->asDate($value);
	}
}
