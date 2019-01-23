<?php
declare(strict_types = 1);

namespace app\modules\import\models\fos;

use app\models\core\traits\ARExtended;
use app\modules\import\models\ImportFosDecomposed;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_users".
 *
 * @property int $id
 * @property int $user_tn
 * @property string $name
 * @property string $sd_id - какой-то внутренний идентификатор НЕ УНИКАЛЬНЫЙ
 * @property int $remote
 * @property string $email_sigma
 * @property string $email_alpha
 * @property int $position_id key to position id
 * @property int $town_id key to town id
 * @property int $domain
 * @property null|int $hr_user_id
 *
 * @property-read ImportFosDecomposed $relDecomposed
 * @property-read ImportFosPositions $relPosition
 * @property-read ImportFosTown $relTown
 * @property-read ImportFosFunctionalBlock $relFunctionalBlock
 * @property-read ImportFosDivisionLevel1 $relDivisionLevel1
 * @property-read ImportFosDivisionLevel2 $relDivisionLevel2
 * @property-read ImportFosDivisionLevel3 $relDivisionLevel3
 * @property-read ImportFosDivisionLevel4 $relDivisionLevel4
 * @property-read ImportFosDivisionLevel5 $relDivisionLevel5
 * @property-read ImportFosCommand $relCommand
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
			['user_tn', 'integer'],
			['user_tn', 'unique'],
			[['position_id', 'town_id'], 'integer'],
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
	 * @return ImportFosDivisionLevel1|ActiveQuery
	 */
	public function getRelDivisionLevel1() {
		return $this->hasOne(ImportFosDivisionLevel1::class, ['id' => 'division_level_1'])->via('relDecomposed');
	}

	/**
	 * @return ImportFosTown|ActiveQuery
	 */
	public function getRelTown() {
		return $this->hasOne(ImportFosTown::class, ['id' => 'town_id']);
	}

	/**
	 * @return ImportFosDivisionLevel2|ActiveQuery
	 */
	public function getRelDivisionLevel2() {
		return $this->hasOne(ImportFosDivisionLevel2::class, ['id' => 'division_level_2'])->via('relDecomposed');
	}

	/**
	 * @return ImportFosDivisionLevel3|ActiveQuery
	 */
	public function getRelDivisionLevel3() {
		return $this->hasOne(ImportFosDivisionLevel3::class, ['id' => 'division_level_3'])->via('relDecomposed');
	}

	/**
	 * @return ImportFosDivisionLevel4|ActiveQuery
	 */
	public function getRelDivisionLevel4() {
		return $this->hasOne(ImportFosDivisionLevel4::class, ['id' => 'division_level_4'])->via('relDecomposed');
	}

	/**
	 * @return ImportFosDivisionLevel5|ActiveQuery
	 */
	public function getRelDivisionLevel5() {
		return $this->hasOne(ImportFosDivisionLevel5::class, ['id' => 'division_level_5'])->via('relDecomposed');
	}

	/**
	 * @return ImportFosCommand|ActiveQuery
	 */
	public function getRelCommand() {
		return $this->hasOne(ImportFosCommand::class, ['command_id' => 'command_id'])->via('relDecomposed');
	}

	/**
	 * @return ImportFosFunctionalBlock|ActiveQuery
	 */
	public function getRelFunctionalBlock() {
		return $this->hasOne(ImportFosFunctionalBlock::class, ['id' => 'functional_block'])->via('relDecomposed');
	}

}
