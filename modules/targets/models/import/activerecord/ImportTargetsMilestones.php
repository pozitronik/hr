<?php
declare(strict_types = 1);

namespace app\modules\targets\models\import\activerecord;

use app\models\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_targets_milestones".
 *
 * @property int $id
 * @property string $milestone
 * @property int $domain
 * @property int|null $hr_target_id
 */
class ImportTargetsMilestones extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_targets_milestones';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['milestone', 'domain'], 'required'],
			[['domain', 'hr_target_id', 'initiative_id'], 'integer'],
			[['milestone'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'milestone' => 'Milestone',
			'domain' => 'Domain',
			'hr_target_id' => 'Hr Target ID',
			'initiative_id' => 'subInitiative id'
		];
	}
}
