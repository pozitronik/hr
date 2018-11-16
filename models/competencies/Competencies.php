<?php
declare(strict_types = 1);

namespace app\models\competencies;

use app\models\core\LCQuery;
use app\models\core\traits\ARExtended;
use app\models\users\Users;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
}
