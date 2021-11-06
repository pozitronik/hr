<?php
declare(strict_types = 1);

namespace app\modules\import\models\fos\activerecord;

use app\components\pozitronik\core\traits\ARExtended;
use app\modules\import\models\fos\ImportFosDecomposed;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_cluster_product".
 *
 * @property int $cluster_id
 * @property string $name
 * @property int $leader_id key to cluster product leader id
 * @property int $leader_it_id key to cluster product leader it id
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
			['cluster_id', 'integer'],
			['cluster_id', 'required'],
			['cluster_id', 'unique'],
			[['leader_id', 'leader_it_id'], 'integer'],
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
			'cluster_id' => 'ID',
			'name' => 'Name',
			'leader_id' => 'key to cluster product leader id',
			'leader_it_id' => 'key to cluster product leader it id'
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelDecomposed():ActiveQuery {
		return $this->hasMany(ImportFosDecomposed::class, ['cluster_product_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelCommand():ActiveQuery {
		return $this->hasMany(ImportFosCommand::class, ['id' => 'command_id'])->via('relDecomposed');
	}
}
