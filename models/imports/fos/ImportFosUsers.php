<?php
declare(strict_types = 1);

namespace app\models\imports\fos;

use app\models\core\traits\ARExtended;
use app\models\imports\ImportFosDecomposed;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_users".
 *
 * @property int $id
 * @property string $name
 * @property string $sd_id
 * @property int $remote
 * @property string $email_sigma
 * @property string $email_alpha
 * @property int $position_id key to position id
 * @property int $functional_block_id key to functional block id
 * @property int $division_level1_id key to division_level1 id
 * @property int $division_level2_id key to division_level2 id
 * @property int $division_level3_id key to division_level3 id
 * @property int $division_level4_id key to division_level4 id
 * @property int $division_level5_id key to division_level5 id
 * @property int $town_id key to town id
 * @property int $domain
 * @property null|int $hr_user_id
 *
 * @property-read ImportFosDecomposed $relDecomposed
 * @property-read ImportFosPositions $relPosition
 * @property-read ImportFosTown $relTown
 */
class ImportFosUsers extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_fos_users';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['position_id', 'functional_block_id', 'division_level1_id', 'division_level2_id', 'division_level3_id', 'division_level4_id', 'division_level5_id', 'town_id'], 'integer'],
			[['name', 'email_sigma', 'email_alpha', 'sd_id'], 'string', 'max' => 255],
			['remote', 'boolean'],
			['domain', 'integer'], ['domain', 'required'],
			['hr_user_id', 'integer']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'sd_id' => 'ШД ID',
			'name' => 'Name',
			'remote' => 'Remote',
			'email_sigma' => 'Email Sigma',
			'email_alpha' => 'Email Alpha',
			'position_id' => 'key to position id',
			'functional_block_id' => 'key to functional block id',
			'division_level1_id' => 'key to division_level1 id',
			'division_level2_id' => 'key to division_level2 id',
			'division_level3_id' => 'key to division_level3 id',
			'division_level4_id' => 'key to division_level4 id',
			'division_level5_id' => 'key to division_level5 id',
			'town_id' => 'key to town id'
		];
	}

	/**
	 * @return ImportFosDecomposed|ActiveQuery
	 */
	public function getRelDecomposed() {
		return $this->hasOne(ImportFosDecomposed::class, ['user_id' => 'id']);
	}

	/**
	 * @return ImportFosPositions|ActiveQuery
	 */
	public function getRelPosition() {
		return $this->hasOne(ImportFosPositions::class, ['id' => 'position_id'])->via('relDecomposed');
	}

	/**
	 * @return ImportFosTown|ActiveQuery
	 */
	public function getRelTown() {
		return $this->hasOne(ImportFosTown::class, ['id' => 'town_id'])->via('relDecomposed');
	}
}
