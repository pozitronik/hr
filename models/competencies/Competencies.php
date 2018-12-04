<?php
declare(strict_types = 1);

namespace app\models\competencies;
/**
 * Глобально нужно переписать хранилище компетенций в некоторую абстрактную сущность a-la DynamicStorage, а компетенции сделать надстройкой над ней.
 * Это позволит абстрагироваться от внутренних методов работы модели, и использовать её в любых местах где нужно динамическое атрибутирование.
 * Саму сущность потребуется утащить в компонент.
 */

use app\helpers\ArrayHelper;
use app\helpers\Date;
use app\models\core\LCQuery;
use app\models\core\SysExceptions;
use app\models\core\traits\ARExtended;
use app\models\relations\RelUsersCompetencies;
use app\models\user\CurrentUser;
use app\models\users\Users;
use app\widgets\alert\AlertModel;
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
 * @property CompetencyField[] $fields//todo переименовать, чтобы не было коллизий с fields()
 *
 * @property int $userFields
 *
 * @property RelUsersCompetencies[]|ActiveQuery $relUsersCompetencies Релейшен к таблице связей с компетенциям
 * @property Users|ActiveQuery $relUsers Пользователи с этой компетенцией
 * @property-read string $categoryName Строковое имя категории
 *
 * @todo: функцию Value, тупо по id юзера и id поля возвращающая его нетипизированное значение.
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
			[['name'], 'required'],
			[['category', 'daddy', 'access'], 'integer'],
			[['deleted'], 'boolean'],
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
			'categoryName' => 'Категория',
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
				AlertModel::SuccessNotify();
				return true;
			}
			AlertModel::ErrorsNotify($this->errors);
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
	 * @return int field_id
	 */
	public function setField(CompetencyField $field, $field_id):int {
		$t = $this->structure;
		if (null === $field_id) $field_id = count($this->structure) + 1;
		$t[$field_id] = [
			'id' => $field_id,
			'name' => $field->name,
			'type' => $field->type,
			'required' => $field->required
		];
		$this->setAndSaveAttribute('structure', $t);
		return $field_id;
	}

	/**
	 * Ищет поле по его имени
	 * @param string $fieldName
	 * @return CompetencyField|null
	 * @throws Throwable
	 */
	public function getFieldByName(string $fieldName):?CompetencyField {
		foreach ($this->structure as $field) {
			if ($fieldName === ArrayHelper::getValue($field, 'name')) {
				return new CompetencyField(array_merge($field, ['competencyId' => $this->id]));
			}
		}
		return null;
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
			$field = new CompetencyField(array_merge($field_data, [
				'competencyId' => $this->id,
				'userId' => $user_id
			]));
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
		$typeClass = CompetencyField::getTypeClass($field->type);
		try {
			$typeClass::setValue($this->id, $field_id, $user_id, $field_value);
		} catch (Throwable $t) {
			SysExceptions::log($t);
			SysExceptions::log(new RuntimeException("Field type {$field->type} not implemented or not configured "), false, true);
		}
	}

	/**
	 * @return Users|ActiveQuery
	 */
	public function getRelUsers() {
		return $this->hasMany(Users::class, ['id' => 'user_id'])->via('relUsersCompetencies');
	}

	/**
	 * @return RelUsersCompetencies[]|ActiveQuery
	 */
	public function getRelUsersCompetencies() {
		return $this->hasMany(RelUsersCompetencies::class, ['competency_id' => 'id']);
	}

	/**
	 * @return string
	 * @throws Throwable
	 */
	public function getCategoryName():string {
		return ArrayHelper::getValue(self::CATEGORIES, $this->category);
	}
}
