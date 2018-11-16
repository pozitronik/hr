<?php
declare(strict_types = 1);

namespace app\models\competencies;

use Yii;
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
 */
class Competencies extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_competencies';
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
