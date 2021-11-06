<?php
declare(strict_types = 1);

namespace app\modules\targets\models\import\activerecord;

use app\components\pozitronik\core\traits\ARExtended;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_targets_targets".
 *
 * @property int $id
 * @property string $target
 * @property int $domain
 * @property int|null $result_id
 * @property string|null $value
 * @property string|null $period
 * @property bool $isYear
 * @property bool $isLK
 * @property bool $isLT
 * @property bool $isCurator
 * @property string|null $comment
 * @property int|null $hr_target_id
 * @property null|int $command_id
 * @property null|int $cluster_id
 *
 * @property ImportTargetsCommands $relCommands
 * @property ImportTargetsMilestones $relMilestones
 */
class ImportTargetsTargets extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_targets_targets';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['target', 'domain'], 'required'],
			[['target', 'comment'], 'string'],
			[['domain', 'result_id', 'hr_target_id', 'milestone_id', 'cluster_id', 'command_id'], 'integer'],
			[['isYear', 'isLK', 'isLT', 'isCurator'], 'boolean'],
			[['value', 'period'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'target' => 'Targets',
			'domain' => 'Domain',
			'result_id' => 'Result ID',
			'value' => 'Value',
			'period' => 'Period',
			'isYear' => 'Is Year',
			'isLK' => 'Is Lk',
			'isLT' => 'Is Lt',
			'isCurator' => 'Is Curator',
			'comment' => 'Comment',
			'hr_target_id' => 'Hr Target ID',
			'milestone_id' => 'Milestone_id',
			'cluster_id' => 'cluster id',
			'command_id' => 'command id',

		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelCommands() {
		return $this->hasOne(ImportTargetsCommands::class, ['id' => 'command_id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelMilestones() {
		return $this->hasOne(ImportTargetsMilestones::class, ['id' => 'milestone_id']);
	}
}
