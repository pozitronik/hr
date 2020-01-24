<?php
declare(strict_types = 1);

namespace app\modules\targets\models\relations;

use app\models\relations\Relations;
use app\models\core\ActiveRecordExtended;

/**
 * This is the model class for table "rel_targets_users".
 *
 * @property int $id
 * @property int $target_id
 * @property int $users_id
 */
class RelTargetsUsers extends ActiveRecordExtended {
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
			[['target_id', 'users_id'], 'required'],
			[['target_id', 'users_id'], 'integer'],
			[['target_id', 'users_id'], 'unique', 'targetAttribute' => ['target_id', 'users_id']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'target_id' => 'Задание',
			'users_id' => 'Исполняющий сотрудник',
		];
	}

}
