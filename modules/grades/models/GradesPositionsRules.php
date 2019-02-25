<?php
declare(strict_types = 1);

namespace app\modules\grades\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "grades_positions_rules".
 *
 * @property int $id
 * @property int $grade_id
 * @property int $position_id
 * @property int $premium_group_id
 */
class GradesPositionsRules extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'grades_positions_rules';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['grade_id', 'position_id'], 'required'],
			[['grade_id', 'position_id', 'premium_group_id'], 'integer']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'grade_id' => 'Grade ID',
			'position_id' => 'Position ID',
			'premium_group_id' => 'Группа премирования'
		];
	}
}
