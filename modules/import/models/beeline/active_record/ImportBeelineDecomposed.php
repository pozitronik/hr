<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline\active_record;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_beeline_decomposed".
 *
 * @property int $id
 * @property int $user_id
 * @property int $business_block_id
 * @property int $functional_block_id
 * @property int $direction_id
 * @property int $department_id
 * @property int $service_id
 * @property int $branch_id
 * @property int $group_id
 * @property int $domain
 */
class ImportBeelineDecomposed extends ActiveRecord {

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_beeline_decomposed';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user_id', 'business_block_id', 'functional_block_id', 'direction_id', 'department_id', 'service_id', 'branch_id', 'group_id', 'domain'], 'integer'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'user_id' => 'User ID',
			'business_block_id' => 'Business Block ID',
			'functional_block_id' => 'Functions Block ID',
			'direction_id' => 'Direction ID',
			'department_id' => 'Department ID',
			'service_id' => 'Service ID',
			'branch_id' => 'Branch ID',
			'group_id' => 'Group ID',
			'level' => 'Level',
			'position_id' => 'Position ID',
			'domain' => 'domain'
		];
	}
}
