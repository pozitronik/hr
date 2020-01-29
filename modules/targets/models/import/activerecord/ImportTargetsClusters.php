<?php

namespace app\modules\targets\models\import\activerecord;

use app\models\core\traits\ARExtended;
use Yii;

/**
 * This is the model class for table "import_targets_clusters".
 *
 * @property int $id
 * @property string $cluster_name
 * @property int $domain
 * @property int|null $hr_group_id
 */
class ImportTargetsClusters extends \yii\db\ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'import_targets_clusters';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['cluster_name', 'domain'], 'required'],
			[['domain', 'hr_group_id'], 'integer'],
			[['cluster_name'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'cluster_name' => 'Cluster Name',
			'domain' => 'Domain',
			'hr_group_id' => 'Hr Group ID',
		];
	}
}
