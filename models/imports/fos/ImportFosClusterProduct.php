<?php
declare(strict_types = 1);

namespace app\models\imports\fos;

use app\models\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_cluster_product".
 *
 * @property int $id
 * @property string $name
 * @property int $leader_id key to cluster product leader id
 * @property int $domain
 * @property null|int $hr_group_id
 */
class ImportFosClusterProduct extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_fos_cluster_product';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['leader_id'], 'integer'],
			[['name'], 'string', 'max' => 255],
			['domain', 'integer'], ['domain', 'required'],
			['hr_group_id', 'integer']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Name',
			'leader_id' => 'key to cluster product leader id'
		];
	}
}
