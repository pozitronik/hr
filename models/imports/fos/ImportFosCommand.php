<?php
declare(strict_types = 1);

namespace app\models\imports\fos;

use app\models\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_command".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $type
 * @property int $cluster_id key to cluster product id
 * @property int $owner_id key to product owner id
 * @property int $domain
 * @property null|int $hr_group_id
 */
class ImportFosCommand extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_fos_command';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			['id', 'integer'],
			[['id', 'domain'], 'unique', 'targetAttribute' => ['id', 'domain']],
			[['cluster_id'], 'integer'],
			[['code', 'name', 'type'], 'string', 'max' => 255],
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
			'code' => 'Code',
			'name' => 'Name',
			'type' => 'Type',
			'cluster_id' => 'key to cluster product id',
			'owner_id' => 'key to product owner id'
		];
	}
}
