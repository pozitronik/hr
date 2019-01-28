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
 * @property-read string $valueJSON
 */
class AttributePropertyScore extends ActiveRecord implements AttributePropertyInterface {

	/**
	 * Конфигурация поддерживаемых типом поисковых условий.
	 * @return array
	 */
	public static function conditionConfig():array {
		return [
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
			'self_score_comment' => 'Крмментарий к самооценке',
			'tl_score_comment' => 'Крмментарий к оценке тимлида',
			'al_score_comment' => 'Крмментарий к оценке ареалида',
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
		return (null !== $record = self::getRecord($attribute_id, $property_id, $user_id))?$record->valueJSON:null;
	}

	/**
	 * Записать в соответствующую таблицу значение поля для этого поля этого атрибута этого юзера
	 * @param int $attribute_id
	 * @param int $property_id
	 * @param int $user_id
	 * @param mixed $value
	 * @return bool
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
	 * @return string
	 */
	public function getValueJSON():string {
		return json_encode([
			$this->getAttributeLabel('self_score_value') => $this->getAttribute('self_score_value'),
			$this->getAttributeLabel('tl_score_value') => $this->getAttribute('tl_score_value'),
			$this->getAttributeLabel('al_score_value') => $this->getAttribute('al_score_value'),
			$this->getAttributeLabel('self_score_comment') => $this->getAttribute('self_score_comment'),
			$this->getAttributeLabel('tl_score_comment') => $this->getAttribute('tl_score_comment'),
			$this->getAttributeLabel('al_score_comment') => $this->getAttribute('al_score_comment'),
		], JSON_UNESCAPED_UNICODE);
	}

}
