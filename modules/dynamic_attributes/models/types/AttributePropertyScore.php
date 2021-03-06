<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\types;

use app\modules\dynamic_attributes\models\DynamicAttributePropertyAggregation;
use app\components\pozitronik\helpers\ArrayHelper;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\dynamic_attributes\widgets\attribute_field_score\ScoreWidget;
use Exception;
use app\components\pozitronik\helpers\Utils;
use Throwable;
use Yii;
use yii\db\Expression;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

/**
 * This is the model class for table "sys_attributes_score".
 *
 * @property int $id
 * @property int $attribute_id ID атрибута
 * @property int $property_id ID поля
 * @property int $user_id ID пользователя
 *
 * @property int $self_score_value [int(11)]  Оценка сотрудника (СО)
 * @property string $self_score_comment [varchar(255)]  Комментарий к самооценке
 * @property int $tl_score_value [int(11)]  Оценка тимлида (TL)
 * @property string $tl_score_comment [varchar(255)]  Комментарий к оценке тимлида
 * @property int $al_score_value [int(11)]  Оценка ареалида (AL)
 * @property string $al_score_comment [varchar(255)]  Комментарий к оценке ареалида
 *
 * @property-read array $valueArray
 * @property-read ScoreProperty $value
 */
class AttributePropertyScore extends AttributeProperty {

	/**
	 * Конфигурация поддерживаемых типом поисковых условий.
	 * @return array
	 */
	public static function conditionConfig():array {
		return [
			//todo: функция условия должна уметь модифицировать выборку для возврата агрегаторов, вроде MAX/MIN
//			['наибольшая самооценка', static function($tableAlias, $searchValue) {
////				return ['=', "$tableAlias.self_score_value", $searchValue];
//				return new Expression("MAX($tableAlias.self_score_value)");
//			}],

			['самооценка равна', static function($tableAlias, $searchValue) {
				return ['=', "$tableAlias.self_score_value", $searchValue];
			}],
			['самооценка не равна', static function($tableAlias, $searchValue) {
				return ['!=', "$tableAlias.self_score_value", $searchValue];
			}],
			['самооценка больше', static function($tableAlias, $searchValue) {
				return ['>', "$tableAlias.self_score_value", $searchValue];
			}],
			['самооценка меньше', static function($tableAlias, $searchValue) {
				return ['<', "$tableAlias.self_score_value", $searchValue];
			}],
			['самооценка меньше или равно', static function($tableAlias, $searchValue) {
				return ['<=', "$tableAlias.self_score_value", $searchValue];
			}],
			['самооценка больше или равно', static function($tableAlias, $searchValue) {
				return ['>=', "$tableAlias.self_score_value", $searchValue];
			}],
			['самооценка заполнена', static function($tableAlias, $searchValue) {
				return ['not', ["$tableAlias.self_score_value" => null]];
			}],
			['самооценка не заполнена', static function($tableAlias, $searchValue) {
				return ['is', "$tableAlias.tl_score_value", new Expression('null')];
			}],/************************************************************/
			['оценка тимлида равна', static function($tableAlias, $searchValue) {
				return ['=', "$tableAlias.tl_score_value", $searchValue];
			}],
			['оценка тимлида не равна', static function($tableAlias, $searchValue) {
				return ['!=', "$tableAlias.tl_score_value", $searchValue];
			}],
			['оценка тимлида больше', static function($tableAlias, $searchValue) {
				return ['>', "$tableAlias.tl_score_value", $searchValue];
			}],
			['оценка тимлида меньше', static function($tableAlias, $searchValue) {
				return ['<', "$tableAlias.tl_score_value", $searchValue];
			}],
			['оценка тимлида меньше или равно', static function($tableAlias, $searchValue) {
				return ['<=', "$tableAlias.tl_score_value", $searchValue];
			}],
			['оценка тимлида больше или равно', static function($tableAlias, $searchValue) {
				return ['>=', "$tableAlias.tl_score_value", $searchValue];
			}],
			['оценка тимлида заполнена', static function($tableAlias, $searchValue) {
				return ['not', ["$tableAlias.tl_score_value" => null]];
			}],
			['оценка тимлида не заполнена', static function($tableAlias, $searchValue) {
				return ['is', "$tableAlias.tl_score_value", new Expression('null')];
			}],/************************************************************/
			['оценка ареалида равна', static function($tableAlias, $searchValue) {
				return ['=', "$tableAlias.al_score_value", $searchValue];
			}],
			['оценка ареалида не равна', static function($tableAlias, $searchValue) {
				return ['!=', "$tableAlias.al_score_value", $searchValue];
			}],
			['оценка ареалида больше', static function($tableAlias, $searchValue) {
				return ['>', "$tableAlias.al_score_value", $searchValue];
			}],
			['оценка ареалида меньше', static function($tableAlias, $searchValue) {
				return ['<', "$tableAlias.al_score_value", $searchValue];
			}],
			['оценка ареалида меньше или равно', static function($tableAlias, $searchValue) {
				return ['<=', "$tableAlias.al_score_value", $searchValue];
			}],
			['оценка ареалида больше или равно', static function($tableAlias, $searchValue) {
				return ['>=', "$tableAlias.al_score_value", $searchValue];
			}],
			['оценка ареалида заполнена', static function($tableAlias, $searchValue) {
				return ['not', ["$tableAlias.al_score_value" => null]];
			}],
			['оценка ареалида не заполнена', static function($tableAlias, $searchValue) {
				return ['is', "$tableAlias.al_score_value", new Expression('null')];
			}],/************************************************************/
			['есть комментарий самооценки', static function($tableAlias, $searchValue) {
				return ['not', ["$tableAlias.self_score_comment" => null]];
			}],
			['есть комментарий оценки тимлида', static function($tableAlias, $searchValue) {
				return ['not', ["$tableAlias.tl_score_comment" => null]];
			}],
			['есть комментарий оценки ареалида', static function($tableAlias, $searchValue) {
				return ['not', ["$tableAlias.al_score_comment" => null]];
			}]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_attributes_score';
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
			'self_score_value' => 'Оценка сотрудника',
			'tl_score_value' => 'Оценка тимлида',
			'al_score_value' => 'Оценка ареалида',
			'self_score_comment' => 'Комментарий к самооценке',
			'tl_score_comment' => 'Комментарий к оценке тимлида',
			'al_score_comment' => 'Комментарий к оценке ареалида',
			'value' => 'Сериализованное значение'
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['attribute_id', 'property_id', 'user_id'], 'required'],
			[['attribute_id', 'property_id', 'user_id'], 'integer'],
			[['self_score_value', 'tl_score_value', 'al_score_value'], 'integer', 'min' => 0, 'max' => 5],
			[['self_score_comment', 'tl_score_comment', 'al_score_comment'], 'string', 'max' => 255],
			[['attribute_id', 'property_id', 'user_id'], 'unique', 'targetAttribute' => ['attribute_id', 'property_id', 'user_id']]
		];
	}

	/**
	 * @param $value
	 * @throws Throwable
	 */
	public function setValue($value):void {
		$this->self_score_value = ArrayHelper::getValue($value, 'selfScoreValue');
		$this->self_score_comment = ArrayHelper::getValue($value, 'selfScoreComment');
		$this->tl_score_value = ArrayHelper::getValue($value, 'tlScoreValue');
		$this->tl_score_comment = ArrayHelper::getValue($value, 'tlScoreComment');
		$this->al_score_value = ArrayHelper::getValue($value, 'alScoreValue');
		$this->al_score_comment = ArrayHelper::getValue($value, 'alScoreComment');
	}

	/**
	 * @return ScoreProperty
	 */
	public function getValue():ScoreProperty {
		return new ScoreProperty([
			'selfScoreValue' => $this->self_score_value,
			'tlScoreValue' => $this->tl_score_value,
			'alScoreValue' => $this->al_score_value,
			'selfScoreComment' => $this->self_score_comment,
			'tlScoreComment' => $this->tl_score_comment,
			'alScoreComment' => $this->al_score_comment
		]);
	}

	/**
	 * Вернуть из соответствующей таблицы значение поля для этого поля этого атрибута этого юзера
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @return mixed
	 */
	public static function loadValue(int $attribute_id, int $property_id, int $user_id) {
		return Yii::$app->cache->getOrSet(static::class."GetValue{$attribute_id},{$property_id},{$user_id}", static function() use ($attribute_id, $property_id, $user_id) {
			return (null !== $record = self::getRecord($attribute_id, $property_id, $user_id))?$record->value:new ScoreProperty();
		});
	}

	/**
	 * Записать в соответствующую таблицу значение поля для этого поля этого атрибута этого юзера
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @param mixed $value
	 * @return bool
	 * @throws Throwable
	 */
	public static function saveValue(int $attribute_id, int $property_id, int $user_id, mixed $value):bool {
		if (null === $record = self::getRecord($attribute_id, $property_id, $user_id)) {
			$record = new self(compact('attribute_id', 'user_id', 'property_id'));
		}

		if (is_string($value)) {//import way.
			$value = json_decode($value, true);
			$record->self_score_value = ArrayHelper::getValue($value, '0');
			$record->self_score_comment = ArrayHelper::getValue($value, '1');
			$record->tl_score_value = ArrayHelper::getValue($value, '2');
			$record->tl_score_comment = ArrayHelper::getValue($value, '3');
			$record->al_score_value = ArrayHelper::getValue($value, '4');
			$record->al_score_comment = ArrayHelper::getValue($value, '5');
		} elseif (is_array($value)) {
			$record->self_score_value = ArrayHelper::getValue($value, 'selfScoreValue');
			$record->self_score_comment = ArrayHelper::getValue($value, 'selfScoreComment');
			$record->tl_score_value = ArrayHelper::getValue($value, 'tlScoreValue');
			$record->tl_score_comment = ArrayHelper::getValue($value, 'tlScoreComment');
			$record->al_score_value = ArrayHelper::getValue($value, 'alScoreValue');
			$record->al_score_comment = ArrayHelper::getValue($value, 'alScoreComment');
		}

		if ($record->save()) {
			Yii::$app->cache->set(static::class."GetValue{$attribute_id},{$property_id},{$user_id}", $record->value);
			return true;
		}
		return false;
	}

	/**
	 * @return array
	 */
	public function getValueArray():array {
		return [
			[
				'label' => $this->getAttributeLabel('self_score_value'),
				'value' => $this->self_score_value
			],
			[
				'label' => $this->getAttributeLabel('tl_score_value'),
				'value' => $this->tl_score_value
			],
			[
				'label' => $this->getAttributeLabel('al_score_value'),
				'value' => $this->al_score_value
			],
			[
				'label' => $this->getAttributeLabel('self_score_comment'),
				'value' => $this->self_score_comment
			],
			[
				'label' => $this->getAttributeLabel('tl_score_comment'),
				'value' => $this->tl_score_comment
			],
			[
				'label' => $this->getAttributeLabel('al_score_comment'),
				'value' => $this->al_score_comment
			]
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
		return $form->field($property, (string)$property->id)->widget(ScoreWidget::class, [
			'model' => $property,
			'attribute' => $property->id,
			'readOnly' => false,
			'showEmpty' => false
		])->label(false);
	}

	/**
	 * Рендер поля просмотра значения свойства
	 * @param array $config Опциональные параметры виджета/поля
	 * @return string
	 * @throws Exception
	 */
	public static function viewField(array $config = []):string {
		return ScoreWidget::widget($config);
	}

	/**
	 * @param DynamicAttributeProperty[] $models
	 * @param bool $dropNullValues
	 * @return ScoreProperty
	 */
	public static function getAverageValue(array $models, bool $dropNullValues = false):ScoreProperty {
		return new ScoreProperty([
			'selfScoreValue' => DynamicAttributePropertyAggregation::AggregateIntAvg(ArrayHelper::getColumn($models, 'value.selfScoreValue'), $dropNullValues),
			'alScoreValue' => DynamicAttributePropertyAggregation::AggregateIntAvg(ArrayHelper::getColumn($models, 'value.alScoreValue'), $dropNullValues),
			'tlScoreValue' => DynamicAttributePropertyAggregation::AggregateIntAvg(ArrayHelper::getColumn($models, 'value.tlScoreValue'), $dropNullValues)
		]);
	}

	/**
	 * @param DynamicAttributeProperty[] $models
	 * @param bool $dropNullValues
	 * @return ScoreProperty
	 */
	public static function getAverageTruncValue(array $models, bool $dropNullValues = false):ScoreProperty {
		return new ScoreProperty([
			'selfScoreValue' => DynamicAttributePropertyAggregation::AggregateIntAvgTrunc(ArrayHelper::getColumn($models, 'value.selfScoreValue'), $dropNullValues),
			'alScoreValue' => DynamicAttributePropertyAggregation::AggregateIntAvgTrunc(ArrayHelper::getColumn($models, 'value.alScoreValue'), $dropNullValues),
			'tlScoreValue' => DynamicAttributePropertyAggregation::AggregateIntAvgTrunc(ArrayHelper::getColumn($models, 'value.tlScoreValue'), $dropNullValues)
		]);
	}

	/**
	 * @param DynamicAttributeProperty[] $models
	 * @return ScoreProperty
	 */
	public static function getHarmonicValue(array $models):ScoreProperty {
		return new ScoreProperty([
			'selfScoreValue' => DynamicAttributePropertyAggregation::AggregateIntHarmonic(ArrayHelper::getColumn($models, 'value.selfScoreValue')),
			'alScoreValue' => DynamicAttributePropertyAggregation::AggregateIntHarmonic(ArrayHelper::getColumn($models, 'value.alScoreValue')),
			'tlScoreValue' => DynamicAttributePropertyAggregation::AggregateIntHarmonic(ArrayHelper::getColumn($models, 'value.tlScoreValue'))
		]);
	}

	/**
	 * @param DynamicAttributeProperty[] $models
	 * @return ScoreProperty
	 */
	public static function getCountValue(array $models):ScoreProperty {
		return new ScoreProperty([
			'selfScoreValue' => DynamicAttributePropertyAggregation::AggregateIntCount(ArrayHelper::getColumn($models, 'value.selfScoreValue')),
			'alScoreValue' => DynamicAttributePropertyAggregation::AggregateIntCount(ArrayHelper::getColumn($models, 'value.alScoreValue')),
			'tlScoreValue' => DynamicAttributePropertyAggregation::AggregateIntCount(ArrayHelper::getColumn($models, 'value.tlScoreValue'))
		]);
	}

	/**
	 * @param DynamicAttributeProperty[] $models
	 * @return ScoreProperty
	 */
	public static function getSumValue(array $models):ScoreProperty {
		return new ScoreProperty([
			'selfScoreValue' => DynamicAttributePropertyAggregation::AggregateIntSum(ArrayHelper::getColumn($models, 'value.selfScoreValue')),
			'alScoreValue' => DynamicAttributePropertyAggregation::AggregateIntSum(ArrayHelper::getColumn($models, 'value.alScoreValue')),
			'tlScoreValue' => DynamicAttributePropertyAggregation::AggregateIntSum(ArrayHelper::getColumn($models, 'value.tlScoreValue'))
		]);
	}

	/**
	 * @param DynamicAttributeProperty[] $models
	 * @param bool $dropNullValues
	 * @return ScoreProperty
	 * @throws Throwable
	 */
	public static function getModaValue(array $models, bool $dropNullValues = true):ScoreProperty {
		return new ScoreProperty([
			'selfScoreValue' => DynamicAttributePropertyAggregation::AggregateIntModa(ArrayHelper::getColumn($models, 'value.selfScoreValue'), $dropNullValues, $maxCounter),
			'selfScoreComment' => Utils::pluralForm($maxCounter, ['пользователь', 'пользователя', 'пользователей']),
			'alScoreValue' => DynamicAttributePropertyAggregation::AggregateIntModa(ArrayHelper::getColumn($models, 'value.alScoreValue'), $dropNullValues, $maxCounter),
			'alScoreComment' => Utils::pluralForm($maxCounter, ['пользователь', 'пользователя', 'пользователей']),
			'tlScoreValue' => DynamicAttributePropertyAggregation::AggregateIntModa(ArrayHelper::getColumn($models, 'value.tlScoreValue'), $dropNullValues, $maxCounter),
			'tlScoreComment' => Utils::pluralForm($maxCounter, ['пользователь', 'пользователя', 'пользователей'])
		]);
	}

	/**
	 * @param DynamicAttributeProperty[] $models
	 * @param bool $dropNullValues
	 * @return ScoreProperty
	 */
	public static function getMinValue(array $models, bool $dropNullValues = true):ScoreProperty {
		return new ScoreProperty([
			'selfScoreValue' => $minValue = DynamicAttributePropertyAggregation::AggregateIntMin(ArrayHelper::getColumn($models, 'value.selfScoreValue'), $dropNullValues),
			'selfScoreComment' => Utils::pluralForm(ArrayHelper::countValue(ArrayHelper::getColumn($models, 'value.selfScoreValue'), $minValue), ['пользователь', 'пользователя', 'пользователей']),
			'alScoreValue' => $minValue = DynamicAttributePropertyAggregation::AggregateIntMin(ArrayHelper::getColumn($models, 'value.alScoreValue'), $dropNullValues),
			'alScoreComment' => Utils::pluralForm(ArrayHelper::countValue(ArrayHelper::getColumn($models, 'value.alScoreValue'), $minValue), ['пользователь', 'пользователя', 'пользователей']),
			'tlScoreValue' => $minValue = DynamicAttributePropertyAggregation::AggregateIntMin(ArrayHelper::getColumn($models, 'value.tlScoreValue'), $dropNullValues),
			'tlScoreComment' => Utils::pluralForm(ArrayHelper::countValue(ArrayHelper::getColumn($models, 'value.tlScoreValue'), $minValue), ['пользователь', 'пользователя', 'пользователей'])
		]);
	}

	/**
	 * @param DynamicAttributeProperty[] $models
	 * @param bool $dropNullValues
	 * @return ScoreProperty
	 */
	public static function getMaxValue(array $models, bool $dropNullValues = true):ScoreProperty {
		return new ScoreProperty([
			'selfScoreValue' => $maxValue = DynamicAttributePropertyAggregation::AggregateIntMax(ArrayHelper::getColumn($models, 'value.selfScoreValue'), $dropNullValues),
			'selfScoreComment' => Utils::pluralForm(ArrayHelper::countValue(ArrayHelper::getColumn($models, 'value.selfScoreValue'), $maxValue), ['пользователь', 'пользователя', 'пользователей']),
			'alScoreValue' => $maxValue = DynamicAttributePropertyAggregation::AggregateIntMax(ArrayHelper::getColumn($models, 'value.alScoreValue'), $dropNullValues),
			'alScoreComment' => Utils::pluralForm(ArrayHelper::countValue(ArrayHelper::getColumn($models, 'value.alScoreValue'), $maxValue), ['пользователь', 'пользователя', 'пользователей']),
			'tlScoreValue' => $maxValue = DynamicAttributePropertyAggregation::AggregateIntMax(ArrayHelper::getColumn($models, 'value.tlScoreValue'), $dropNullValues),
			'tlScoreComment' => Utils::pluralForm(ArrayHelper::countValue(ArrayHelper::getColumn($models, 'value.tlScoreValue'), $maxValue), ['пользователь', 'пользователя', 'пользователей'])
		]);
	}

	/**
	 * @param DynamicAttributeProperty[] $models
	 * @param bool $dropNullValues
	 * @return ScoreProperty
	 */
	public static function getMedianValue(array $models, bool $dropNullValues = true):ScoreProperty {
		return new ScoreProperty([
			'selfScoreValue' => DynamicAttributePropertyAggregation::AggregateIntMedian(ArrayHelper::getColumn($models, 'value.selfScoreValue'), $dropNullValues),
			'alScoreValue' => DynamicAttributePropertyAggregation::AggregateIntMedian(ArrayHelper::getColumn($models, 'value.alScoreValue'), $dropNullValues),
			'tlScoreValue' => DynamicAttributePropertyAggregation::AggregateIntMedian(ArrayHelper::getColumn($models, 'value.tlScoreValue'), $dropNullValues)
		]);
	}

	/**
	 * Конфигурация поддерживаемых типом агрегаторов
	 * @return array
	 */
	public static function aggregationConfig():array {
		return [//аггрегаторы применяются только к числовым значениям
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
	 * @return DynamicAttributePropertyAggregation|null -- результат агрегации в модели
	 * @throws Throwable
	 */
	public static function applyAggregation(array $models, int $aggregation, bool $dropNullValues = false):?DynamicAttributePropertyAggregation {
		switch ($aggregation) {
			case DynamicAttributePropertyAggregation::AGGREGATION_AVG:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_SCORE,
					'value' => self::getAverageValue($models, $dropNullValues)
				]);
			case DynamicAttributePropertyAggregation::AGGREGATION_AVG_TRUNC:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_SCORE,
					'value' => self::getAverageTruncValue($models, $dropNullValues)
				]);
			case DynamicAttributePropertyAggregation::AGGREGATION_HARMONIC:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_SCORE,
					'value' => self::getHarmonicValue($models)
				]);
			case DynamicAttributePropertyAggregation::AGGREGATION_MODA:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_SCORE,
					'value' => self::getModaValue($models, $dropNullValues)
				]);
			case DynamicAttributePropertyAggregation::AGGREGATION_COUNT:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_SCORE,
					'value' => self::getCountValue($models)
				]);
			case DynamicAttributePropertyAggregation::AGGREGATION_MIN:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_SCORE,
					'value' => self::getMinValue($models, $dropNullValues)
				]);
			case DynamicAttributePropertyAggregation::AGGREGATION_MAX:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_SCORE,
					'value' => self::getMaxValue($models, $dropNullValues)
				]);
			case DynamicAttributePropertyAggregation::AGGREGATION_SUM:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_SCORE,
					'value' => self::getSumValue($models)
				]);
			case DynamicAttributePropertyAggregation::AGGREGATION_MEDIAN:
				return new DynamicAttributePropertyAggregation([
					'type' => DynamicAttributeProperty::PROPERTY_SCORE,
					'value' => self::getMedianValue($models)
				]);
			default:
				return DynamicAttributePropertyAggregation::AGGREGATION_UNSUPPORTED;
		}

	}

}
