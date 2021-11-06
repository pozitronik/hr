<?php
declare(strict_types = 1);

namespace app\modules\targets\models\relations;

use app\components\pozitronik\core\traits\Relations;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "rel_targets_groups".
 *
 * @property int $id
 * @property int $target_id
 * @property int $group_id
 */
class RelTargetsGroups extends ActiveRecord {
	use Relations;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rel_targets_groups';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['target_id', 'group_id'], 'required'],
			[['target_id', 'group_id'], 'integer'],
			[['target_id', 'group_id'], 'unique', 'targetAttribute' => ['target_id', 'group_id']]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'target_id' => 'Задание',
			'group_id' => 'Исполняющая группа',
		];
	}

}
