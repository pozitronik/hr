<?php
declare(strict_types = 1);

namespace app\models\competencies\types;

use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "sys_competencies_percent".
 *
 * @property int $id
 * @property int $competency_id ID компетенции
 * @property int $field_id ID поля
 * @property int $user_id ID пользователя
 * @property int $value Значение
 */
class CompetencyFieldPercent extends ActiveRecord implements DataFieldInterface {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_competencies_percent';
	}

	/**
	 * Конфигурация поддерживаемых типом поисковых условий.
	 * @return array
	 */
	public static function conditionConfig():array {
		return [
			['равно', function($searchValue) {
				return ['=', self::tableName().".value", $searchValue];
			}],
			['не равно', function($searchValue) {
				return ['!=', self::tableName().".value", $searchValue];
			}],
			['больше', function($searchValue) {
				return ['>', self::tableName().".value", $searchValue];
			}],
			['меньше', function($searchValue) {
				return ['<', self::tableName().".value", $searchValue];
			}],
			['меньше или равно', function($searchValue) {
				return ['<=', self::tableName().".value", $searchValue];
			}],
			['больше или равно', function($searchValue) {
				return ['>=', self::tableName().".value", $searchValue];
			}],
			['заполнено', function($searchValue) {
				return ['not', self::tableName().".value", null];
			}],
			['не заполнено', function($searchValue) {
				return ['is', self::tableName().".value", new Expression('null')];
			}]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'competency_id' => 'ID компетенции',
			'field_id' => 'ID поля',
			'user_id' => 'ID пользователя',
			'value' => 'Значение'
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['competency_id', 'field_id', 'user_id'], 'required'],
			[['competency_id', 'field_id', 'user_id', 'value'], 'integer'],
			[['competency_id', 'field_id', 'user_id'], 'unique', 'targetAttribute' => ['competency_id', 'field_id', 'user_id']]
		];
	}

	/**
	 * Вернуть из соответствующей таблицы значение поля для этого поля этой компетенции этого юзера
	 * @param int $competency_id
	 * @param int $field_id
	 * @param int $user_id
	 * @return mixed
	 */
	public static function getValue(int $competency_id, int $field_id, int $user_id) {
		return (null !== $record = self::getRecord($competency_id, $field_id, $user_id))?$record->value:null;
	}

	/**
	 * Записать в соответствующую таблицу значение поля для этого поля этой компетенции этого юзера
	 * @param int $competency_id
	 * @param int $field_id
	 * @param int $user_id
	 * @param mixed $value
	 * @return mixed
	 */
	public static function setValue(int $competency_id, int $field_id, int $user_id, $value) {
		if (null === $record = self::getRecord($competency_id, $field_id, $user_id)) {
			$record = new self(compact('competency_id', 'user_id', 'field_id', 'value'));
		} else {
			$record->setAttributes(compact('competency_id', 'user_id', 'field_id', 'value'));
		}

		return $record->save();
	}

	/**
	 * Поиск соответствующей записи по подходящим параметрам
	 * @param int $competency_id
	 * @param int $field_id
	 * @param int $user_id
	 * @return self|null
	 */
	public static function getRecord(int $competency_id, int $field_id, int $user_id):?self {
		return self::find()->where(compact('competency_id', 'field_id', 'user_id'))->one();
	}
}
