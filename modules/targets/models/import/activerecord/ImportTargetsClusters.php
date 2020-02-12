<?php
declare(strict_types = 1);

namespace app\modules\targets\models\import\activerecord;

use pozitronik\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_targets_clusters".
 *
 * @property int $id
 * @property string $cluster_name
 * @property int $domain
 * @property int|null $hr_group_id
 */
class ImportTargetsClusters extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_targets_clusters';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['cluster_name', 'domain'], 'required'],
			[['domain', 'hr_group_id'], 'integer'],
			[['cluster_name'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'cluster_name' => 'Cluster Name',
			'domain' => 'Domain',
			'hr_group_id' => 'Hr Group ID',
		];
	}
}
