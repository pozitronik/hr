<?php
declare(strict_types = 1);

namespace app\modules\salary\models\references;

use pozitronik\references\models\Reference;

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
			[['id', 'usedCount'], 'integer'],
			[['deleted'], 'boolean']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'usedCount' => 'Использований'
		];
	}
}
