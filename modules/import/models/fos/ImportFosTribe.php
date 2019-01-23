<?php
declare(strict_types = 1);

namespace app\modules\import\models\fos;

use app\models\core\traits\ARExtended;
use app\modules\import\models\ImportFosDecomposed;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_tribe".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $leader_id key to tribe leader id
 * @property int $leader_it_id key to tribe leader it id
 * @property int $domain
 * @property null|int $hr_group_id
 *
 * @property-read ImportFosDecomposed[] $relDecomposed
 * @property-read ImportFosClusterProduct[] $relCluster
 * @property-read ImportFosChapter[] $relChapter
 */
class ImportFosTribe extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_fos_tribe';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			['id', 'integer'],
			[['id', 'domain'], 'unique', 'targetAttribute' => ['id', 'domain']],
			[['leader_id', 'leader_it_id'], 'integer'],
			[['code', 'name'], 'string', 'max' => 255],
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
			'leader_id' => 'key to tribe leader id',
			'leader_it_id' => 'key to tribe leader it id'
		];
	}

	/**
	 * @return ImportFosDecomposed[]|ActiveQuery
	 */
	public function getRelDecomposed() {
		return $this->hasMany(ImportFosDecomposed::class, ['tribe_id' => 'pkey']);
	}

	/**
	 * @return ImportFosClusterProduct[]|ActiveQuery
	 */
	public function getRelCluster() {
		return $this->hasMany(ImportFosClusterProduct::class, ['pkey' => 'cluster_product_id'])->via('relDecomposed');
	}

	/**
	 * @return ImportFosChapter[]|ActiveQuery
	 */
	public function getRelChapter() {
		return $this->hasMany(ImportFosChapter::class, ['pkey' => 'chapter_id'])->via('relDecomposed');
	}
}
