<?php
declare(strict_types = 1);

namespace app\models\competencies;

use app\helpers\ArrayHelper;
use app\helpers\Date;
use app\models\competencies\types\CompetencyFieldBoolean;
use app\models\competencies\types\CompetencyFieldDate;
use app\models\competencies\types\CompetencyFieldInteger;
use app\models\competencies\types\CompetencyFieldPercent;
use app\models\competencies\types\CompetencyFieldRange;
use app\models\competencies\types\CompetencyFieldString;
use app\models\competencies\types\CompetencyFieldTime;
use app\models\core\LCQuery;
use app\models\core\SysExceptions;
use app\models\core\traits\ARExtended;
use app\models\user\CurrentUser;
use app\models\users\Users;
use RuntimeException;
use Throwable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "sys_competencies".
 * Компетенция - сугубо динамическая штука, состоящая из произвольного набора атрибутов.
 * Хранить структуру будем в JSON-поле таблицы. Редактор компетенций редактирует эту структуру: набор, порядок и тип полей, а также их валидацию.
 * @property int $id
 * @property string $name Название компетенции
 * @property int $category Категория
 * @property int $daddy Создатель
 * @property string $create_date Дата создания
 * @property array $structure Структура
 * @property integer $access
 * @property int $deleted Флаг удаления
 *
 * @property CompetencyField[] $fields
 *
 * @property int $userFields
 * @property-read Users|ActiveQuery $affected_users Пользователи с этой компетенцией
 */
class Competencies extends ActiveRecord {
	use ARExtended;

	public const CATEGORIES = [/*Ну хер знает*/
		0 => 'Общая категория',
		1 => 'Обучение',
		2 => 'Навык'
	];

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_competencies';
	}

	/**
	 * @return LCQuery
	 */
	public static function find():LCQuery {
		return new LCQuery(static::class);
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name', 'structure'], 'required'],
			[['category', 'daddy', 'deleted', 'access'], 'integer'],
			[['create_date', 'structure'], 'safe'],
			[['name'], 'string', 'max' => 255]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название компетенции',
			'category' => 'Категория',
			'daddy' => 'Создатель',
			'create_date' => 'Дата создания',
			'structure' => 'Структура',
			'access' => 'Доступ',
			'deleted' => 'Флаг удаления'
		];
	}

	/**
	 * @param $paramsArray
	 * @return bool
	 * @throws Exception
	 */
	public function createCompetency($paramsArray):bool {
		$transaction = self::getDb()->beginTransaction();
		if ($this->loadArray($paramsArray)) {
			$this->create_date = Date::lcDate();
			$this->daddy = CurrentUser::Id();
			$this->structure = [];
			if ($this->save()) {
				$transaction->commit();
				return true;
			}
		}
		$transaction->rollBack();
		return false;
	}

	/**
	 * @param $paramsArray
	 * @return bool
	 */
	public function updateCompetency($paramsArray):bool {
		if ($this->loadArray($paramsArray)) {
			return $this->save();
		}
		return false;
	}

	/**
	 * @param CompetencyField $field
	 * @param int|null $field_id
	 */
	public function setField(CompetencyField $field, $field_id):void {
		$t = $this->structure;
		if (null === $field_id) {
			$t[count($this->structure) + 1] = [
				'id' => count($this->structure) + 1,
				'name' => $field->name,
				'type' => $field->type,
				'required' => $field->required
			];
		} else {
			$t[$field_id] = [
				'id' => $field_id,
				'name' => $field->name,
				'type' => $field->type,
				'required' => $field->required
			];
		}
		$this->setAndSaveAttribute('structure', $t);
	}

	/**
	 * Ищет поле по индексу
	 * @param int $id
	 * @param null $throw
	 * @return CompetencyField|false
	 * @throws Throwable
	 */
	public function getFieldById(int $id, $throw = null):?CompetencyField {
		if (null !== $data = ArrayHelper::getValue($this->structure, $id)) return new CompetencyField(array_merge($data, ['competencyId' => $this->id]));
		if (null !== $throw) SysExceptions::log($throw, $throw, true);
		return false;
	}

	/**
	 * Массив компетенций пользователя пользователя
	 * @param int $user_id
	 * @return CompetencyField[]
	 */
	public function getUserFields(int $user_id):array {
		$result = [];
		foreach ($this->structure as $field_data) {
			$field = new CompetencyField($field_data);
			$field->competencyId = $this->id;//todo move to initializer
			$field->userId = $user_id;
			$result[] = $field;
		}
		return $result;
	}

	/**
	 * @param int $user_id
	 * @param array $values
	 * @throws Throwable
	 */
	public function setUserFields(int $user_id, array $values):void {
		foreach ($values as $key => $value) {
			$this->setUserField($user_id, $key, $value);
		}
	}

	/**
	 * Устанавливает значение атрибута компетенции для пользователя
	 * @param int $user_id
	 * @param int $field_id
	 * @param $field_value
	 * @throws Throwable
	 */
	public function setUserField(int $user_id, int $field_id, $field_value):void {
		$field = $this->getFieldById($field_id);

		switch ($field->type) {//todo: проверить все методы на соответсиве параметрам
			case 'boolean':
				CompetencyFieldBoolean::setValue($this->id, $field_id, $user_id, $field_value);
			break;
			case 'date':
				CompetencyFieldDate::setValue($this->id, $field_id, $user_id, $field_value);
			break;
			case 'integer':
				CompetencyFieldInteger::setValue($this->id, $field_id, $user_id, $field_value);
			break;
			case 'percent':
				CompetencyFieldPercent::setValue($this->id, $field_id, $user_id, $field_value);
			break;
			case 'range':
				CompetencyFieldRange::setValue($this->id, $field_id, $user_id, $field_value);
			break;
			case 'string':
				CompetencyFieldString::setValue($this->id, $field_id, $user_id, $field_value);
			break;
			case 'time':
				CompetencyFieldTime::setValue($this->id, $field_id, $user_id, $field_value);
			break;
			default:
				throw new RuntimeException("Field type not implemented: {$field->type}");
			break;
		}
	}
}
