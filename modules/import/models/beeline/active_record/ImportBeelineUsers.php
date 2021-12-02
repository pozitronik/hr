<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline\active_record;

use app\components\pozitronik\core\traits\ARExtended;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_users".
 *
 * @property int $id
 * @property int|null $user_tn
 * @property string $name
 * @property string $position Название должности
 * @property int $level ceo level
 * @property int $domain
 * @property null|string $user_type
 * @property null|string $affiliation
 * @property null|string $position_profile_number
 * @property bool $is_boss
 * @property null|string $company_code
 * @property null|string $cbo
 * @property null|string $location
 *
 * @property null|int $hr_user_id
 *
 * @property-read ImportBeelineDecomposed $relDecomposed
 *
 * @property-read null|ImportBeelineBusinessBlock $relBeelineBusinessBlock
 * @property-read null|ImportBeelineFunctionalBlock $relBeelineFunctionalBlock
 * @property-read null|ImportBeelineDirection $relBeelineDirection
 * @property-read null|ImportBeelineDepartment $relBeelineDepartment
 * @property-read null|ImportBeelineService $relBeelineService
 * @property-read null|ImportBeelineBranch $relBeelineBranch
 * @property-read null|ImportBeelineGroup $relBeelineGroup
 *
 */
class ImportBeelineUsers extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_beeline_users';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			['user_tn', 'integer'],//kinda
			[['level'], 'integer'],
			[['name', 'position', 'user_type', 'affiliation', 'position_profile_number', 'company_code', 'cbo', 'location'], 'string', 'max' => 255],
			['domain', 'integer'], ['domain', 'required'],
			['hr_user_id', 'integer'],
			['is_boss', 'boolean']
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelDecomposed():ActiveQuery {
		return $this->hasOne(ImportBeelineDecomposed::class, ['user_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelBeelineBusinessBlock():ActiveQuery {
		return $this->hasOne(ImportBeelineBusinessBlock::class, ['id' => 'business_block_id'])->via('relDecomposed');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelBeelineFunctionalBlock():ActiveQuery {
		return $this->hasOne(ImportBeelineFunctionalBlock::class, ['id' => 'functional_block_id'])->via('relDecomposed');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelBeelineDirection():ActiveQuery {
		return $this->hasOne(ImportBeelineDirection::class, ['id' => 'direction_id'])->via('relDecomposed');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelBeelineDepartment():ActiveQuery {
		return $this->hasOne(ImportBeelineDepartment::class, ['id' => 'department_id'])->via('relDecomposed');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelBeelineService():ActiveQuery {
		return $this->hasOne(ImportBeelineService::class, ['id' => 'service_id'])->via('relDecomposed');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelBeelineBranch():ActiveQuery {
		return $this->hasOne(ImportBeelineBranch::class, ['id' => 'branch_id'])->via('relDecomposed');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelBeelineGroup():ActiveQuery {
		return $this->hasOne(ImportBeelineGroup::class, ['id' => 'group_id'])->via('relDecomposed');
	}

}
