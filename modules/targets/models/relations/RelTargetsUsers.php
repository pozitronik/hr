<?php
declare(strict_types = 1);

namespace app\modules\targets\models\relations;

use app\models\relations\Relations;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "rel_targets_users".
 *
 * @property int $id
 * @property int $target_id
 * @property int $user_id
 */
class RelTargetsUsers extends ActiveRecord {
	use Relations;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_targets_users';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['target_id', 'user_id'], 'required'],
			[['target_id', 'user_id'], 'integer'],
			[['target_id', 'user_id'], 'unique', 'targetAttribute' => ['target_id', 'user_id']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'target_id' => 'Задание',
			'user_id' => 'Исполняющий сотрудник',
		];
	}

}
