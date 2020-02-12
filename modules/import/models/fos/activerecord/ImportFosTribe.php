<?php
declare(strict_types = 1);

namespace app\modules\import\models\fos\activerecord;

use pozitronik\core\traits\ARExtended;
use app\modules\import\models\fos\ImportFosDecomposed;
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
			['tribe_id', 'integer'],
			['tribe_id', 'required'],
			['tribe_id', 'unique'],
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
			'tribe_id' => 'ID',
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
		return $this->hasMany(ImportFosDecomposed::class, ['tribe_id' => 'id']);
	}

	/**
	 * @return ImportFosClusterProduct[]|ActiveQuery
	 */
	public function getRelCluster() {
		return $this->hasMany(ImportFosClusterProduct::class, ['id' => 'cluster_product_id'])->via('relDecomposed');
	}

	/**
	 * @return ImportFosChapter[]|ActiveQuery
	 */
	public function getRelChapter() {
		return $this->hasMany(ImportFosChapter::class, ['id' => 'chapter_id'])->via('relDecomposed');
	}
}
