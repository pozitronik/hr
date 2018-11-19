<?php
declare(strict_types = 1);

namespace app\models\relations;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "rel_users_competencies".
 *
 * @property int $id
 * @property int $user_id
 * @property int $competency_id
 */
class RelUsersCompetencies extends ActiveRecord {
	use Relations;
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_users_competencies';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user_id', 'competency_id'], 'required'],
			[['user_id', 'competency_id'], 'integer'],
			[['user_id', 'competency_id'], 'unique', 'targetAttribute' => ['user_id', 'competency_id']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'user_id' => 'User ID',
			'competency_id' => 'Competency ID'
		];
	}
}
