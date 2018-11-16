<?php
declare(strict_types = 1);

namespace app\models\competencies;

use app\helpers\Date;
use app\models\core\LCQuery;
use app\models\core\traits\ARExtended;
use app\models\user\CurrentUser;
use app\models\users\Users;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "sys_competencies".
 *
 * @property int $id
 * @property string $name Название компетенции
 * @property int $category Категория
 * @property int $daddy Создатель
 * @property string $create_date Дата создания
 * @property array $structure Структура
 * @property int $deleted Флаг удаления
 *
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
			[['category', 'daddy', 'deleted'], 'integer'],
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
			$this->structure = ['test' => 'test'];
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
}
