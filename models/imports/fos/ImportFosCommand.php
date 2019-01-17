<?php
declare(strict_types = 1);

namespace app\models\imports\fos;

/**
 * This is the model class for table "import_fos_command".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $type
 * @property int $cluster_id key to cluster product id
 * @property int $owner_id key to product owner id
 */
class ImportFosCommand extends \yii\db\ActiveRecord {
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
			[['cluster_id'], 'required'],
			[['cluster_id', 'owner_id'], 'integer'],
			[['code', 'name', 'type'], 'string', 'max' => 255]
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
