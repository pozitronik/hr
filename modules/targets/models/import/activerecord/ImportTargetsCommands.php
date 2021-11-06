<?php
declare(strict_types = 1);

namespace app\modules\targets\models\import\activerecord;

use app\components\pozitronik\core\traits\ARExtended;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_targets_commands".
 *
 * @property int $id
 * @property string $command_name
 * @property string $command_id
 * @property int $domain
 * @property int|null $hr_group_id
 *
 * @property ImportTargetsTargets[] $relTargets
 * @property ImportTargetsClusters[] $relCluster
 */
class ImportTargetsCommands extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_targets_commands';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['command_name', 'command_id', 'domain'], 'required'],
			[['domain', 'hr_group_id'], 'integer'],
			[['command_name', 'command_id'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'command_name' => 'Command Name',
			'command_id' => 'Command ID',
			'domain' => 'Domain',
			'hr_group_id' => 'Hr Group ID',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelCluster() {
		return $this->hasOne(ImportTargetsClusters::class, ['id' => 'cluster_id'])->via('relTargets');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelTargets() {
		return $this->hasMany(ImportTargetsTargets::class, ['command_id' => 'id']);
	}
}
