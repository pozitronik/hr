<?php
declare(strict_types = 1);

namespace app\models\dynamic_attributes\types;

use app\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use yii\db\Expression;

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
 */
class AttributePropertyScore extends ActiveRecord implements AttributePropertyInterface {

	/**
	 * Конфигурация поддерживаемых типом поисковых условий.
	 * @return array
	 */
	public static function conditionConfig():array {
		return [
			['самооценка равна', function($tableAlias, $searchValue) {
				return ['=', "$tableAlias.self_score_value", $searchValue];
			}],
			['самооценка не равна', function($tableAlias, $searchValue) {
				return ['!=', "$tableAlias.self_score_value", $searchValue];
			}],
			['самооценка больше', function($tableAlias, $searchValue) {
				return ['>', "$tableAlias.self_score_value", $searchValue];
			}],
			['самооценка меньше', function($tableAlias, $searchValue) {
				return ['<', "$tableAlias.self_score_value", $searchValue];
			}],
			['самооценка меньше или равно', function($tableAlias, $searchValue) {
				return ['<=', "$tableAlias.self_score_value", $searchValue];
			}],
			['самооценка больше или равно', function($tableAlias, $searchValue) {
				return ['>=', "$tableAlias.self_score_value", $searchValue];
			}],
			['самооценка заполнена', function($tableAlias, $searchValue) {
				return ['not', ["$tableAlias.self_score_value" => null]];
			}],
			['самооценка не заполнена', function($tableAlias, $searchValue) {
				return ['is', "$tableAlias.tl_score_value", new Expression('null')];
			}],/************************************************************/
			['оценка тимлида равна', function($tableAlias, $searchValue) {
				return ['=', "$tableAlias.tl_score_value", $searchValue];
			}],
			['оценка тимлида не равна', function($tableAlias, $searchValue) {
				return ['!=', "$tableAlias.tl_score_value", $searchValue];
			}],
			['оценка тимлида больше', function($tableAlias, $searchValue) {
				return ['>', "$tableAlias.tl_score_value", $searchValue];
			}],
			['оценка тимлида меньше', function($tableAlias, $searchValue) {
				return ['<', "$tableAlias.tl_score_value", $searchValue];
			}],
			['оценка тимлида меньше или равно', function($tableAlias, $searchValue) {
				return ['<=', "$tableAlias.tl_score_value", $searchValue];
			}],
			['оценка тимлида больше или равно', function($tableAlias, $searchValue) {
				return ['>=', "$tableAlias.tl_score_value", $searchValue];
			}],
			['оценка тимлида заполнена', function($tableAlias, $searchValue) {
				return ['not', ["$tableAlias.tl_score_value" => null]];
			}],
			['оценка тимлида не заполнена', function($tableAlias, $searchValue) {
				return ['is', "$tableAlias.tl_score_value", new Expression('null')];
			}],/************************************************************/
			['оценка ареалида равна', function($tableAlias, $searchValue) {
				return ['=', "$tableAlias.al_score_value", $searchValue];
			}],
			['оценка ареалида не равна', function($tableAlias, $searchValue) {
				return ['!=', "$tableAlias.al_score_value", $searchValue];
			}],
			['оценка ареалида больше', function($tableAlias, $searchValue) {
				return ['>', "$tableAlias.al_score_value", $searchValue];
			}],
			['оценка ареалида меньше', function($tableAlias, $searchValue) {
				return ['<', "$tableAlias.al_score_value", $searchValue];
			}],
			['оценка ареалида меньше или равно', function($tableAlias, $searchValue) {
				return ['<=', "$tableAlias.al_score_value", $searchValue];
			}],
			['оценка ареалида больше или равно', function($tableAlias, $searchValue) {
				return ['>=', "$tableAlias.al_score_value", $searchValue];
			}],
			['оценка ареалида заполнена', function($tableAlias, $searchValue) {
				return ['not', ["$tableAlias.al_score_value" => null]];
			}],
			['оценка ареалида не заполнена', function($tableAlias, $searchValue) {
				return ['is', "$tableAlias.al_score_value", new Expression('null')];
			}],
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
			'self_score_value' => 'Оценка сотрудника (СО)',
			'tl_score_value' => 'Оценка тимлида (TL)',
			'al_score_value' => 'Оценка ареалида (AL)',
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
	 * @return mixed
	 */
	public static function getValue(int $attribute_id, int $property_id, int $user_id) {
		return (null !== $record = self::getRecord($attribute_id, $property_id, $user_id))?$record->valueArray:[];
	}

	/**
	 * Записать в соответствующую таблицу значение поля для этого поля этого атрибута этого юзера
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @param mixed $value
	 * @return bool
	 * @throws \Throwable
	 */
	public static function setValue(int $attribute_id, int $property_id, int $user_id, $value):bool {
		$deserializedValue = json_decode($value, true);
		if (null === $record = self::getRecord($attribute_id, $property_id, $user_id)) {
			$record = new self(compact('attribute_id', 'user_id', 'property_id'));
		}
		$record->self_score_value = ArrayHelper::getValue($deserializedValue, '0');
		$record->self_score_comment = ArrayHelper::getValue($deserializedValue, '1');

		$record->tl_score_value = ArrayHelper::getValue($deserializedValue, '2');
		$record->tl_score_comment = ArrayHelper::getValue($deserializedValue, '3');

		$record->al_score_value = ArrayHelper::getValue($deserializedValue, '4');
		$record->al_score_comment = ArrayHelper::getValue($deserializedValue, '5');
		return $record->save();
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

}
