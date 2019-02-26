<?php
declare(strict_types = 1);

namespace app\modules\grades\models\references;


use app\modules\references\models\Reference;

/**
 * @property int $id
 * @property string $name
 */
class RefGrades extends Reference {
	public $menuCaption = 'Грейды';
	public $menuIcon = false;


	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'ref_salary_grades';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['name'], 'string', 'max' => 255],
			[['id', 'deleted', 'usedCount'], 'integer']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Name'
		];
	}
}
