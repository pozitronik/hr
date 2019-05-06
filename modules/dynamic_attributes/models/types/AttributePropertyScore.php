<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\models\types;

use pozitronik\helpers\ArrayHelper;
use app\models\core\ActiveRecordExtended;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\dynamic_attributes\widgets\attribute_field_score\ScoreWidget;
use Exception;
use Throwable;
use Yii;
use yii\db\ActiveRecord;
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
 * @property-read ScoreProperty $scoreValue
 */
class AttributePropertyScore extends ActiveRecordExtended implements AttributePropertyInterface {

	/**
	 * Конфигурация поддерживаемых типом поисковых условий.
	 * @return array
	 */
	public static function conditionConfig():array {
		return [
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
	 * Вернуть из соответствующей таблицы значение поля для этого поля этого атрибута этого юзера
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @param bool $formatted
	 * @return mixed
	 */
	public static function getValue(int $attribute_id, int $property_id, int $user_id, bool $formatted = false) {
		return Yii::$app->cache->getOrSet(static::class."GetValue{$attribute_id},{$property_id},{$user_id}", static function() use ($attribute_id, $property_id, $user_id) {
			return (null !== $record = self::getRecord($attribute_id, $property_id, $user_id))?$record->scoreValue:new ScoreProperty();
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
	public static function setValue(int $attribute_id, int $property_id, int $user_id, $value):bool {
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
			Yii::$app->cache->set(static::class."GetValue{$attribute_id},{$property_id},{$user_id}", $record->scoreValue);
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
	 * @return mixed
	 */
	public function getScoreValue() {
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
	 * Функция отдаёт форму поля для редактирования значения свойства
	 * @param ActiveForm $form
	 * @param DynamicAttributeProperty $property
	 * @return ActiveField
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
}
