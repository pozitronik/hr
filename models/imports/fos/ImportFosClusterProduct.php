<?php
declare(strict_types = 1);

namespace app\models\imports\fos;

use app\models\core\traits\ARExtended;
use app\models\imports\ImportFosDecomposed;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_cluster_product".
 *
 * @property int $id
 * @property string $name
 * @property int $leader_id key to cluster product leader id
 * @property int $domain
 * @property null|int $hr_group_id
 *
 * @property-read ImportFosDecomposed[] $relDecomposed
 * @property-read ImportFosCommand[] $relCommand
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
			['id', 'integer'],
			[['id', 'domain'], 'unique', 'targetAttribute' => ['id', 'domain']],
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

	/**
	 * @return ImportFosDecomposed[]|ActiveQuery
	 */
	public function getRelDecomposed() {
		return $this->hasMany(ImportFosDecomposed::class, ['cluster_product_id' => 'id']);
	}

	/**
	 * @return ImportFosCommand[]|ActiveQuery
	 */
	public function getRelCommand() {
		return $this->hasMany(ImportFosCommand::class, ['id' => 'command_id'])->via('relDecomposed');
	}
}
