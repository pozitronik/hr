<?php

namespace app\modules\targets\models\import\activerecord;

use app\models\core\traits\ARExtended;
use Yii;

/**
 * This is the model class for table "import_targets_commands".
 *
 * @property int $id
 * @property string $command_name
 * @property string $command_id
 * @property int $domain
 * @property int|null $hr_group_id
 */
class ImportTargetsCommands extends \yii\db\ActiveRecord {
	use ARExtended;
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'import_targets_commands';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['command_name', 'command_id', 'domain'], 'required'],
			[['domain', 'hr_group_id'], 'integer'],
			[['command_name', 'command_id'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'command_name' => 'Command Name',
			'command_id' => 'Command ID',
			'domain' => 'Domain',
			'hr_group_id' => 'Hr Group ID',
		];
	}
}
