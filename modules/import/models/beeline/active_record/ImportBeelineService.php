<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline\active_record;

use yii\db\ActiveQuery;

/**
 * Class ImportBeelineBusinessBlock
 * @property-read ImportBeelineDecomposed[] $relDecomposed
 * @property-read ImportBeelineBranch[] $relBranch
 */
class ImportBeelineService extends ImportBeelineBase {

	/**
	 * @inheritDoc
	 */
	public static function tableName():string {
		return 'import_beeline_service';
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelDecomposed():ActiveQuery {
		return $this->hasMany(ImportBeelineDecomposed::class, ['service_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelBranch():ActiveQuery {
		return $this->hasMany(ImportBeelineBranch::class, ['id' => 'branch_id'])->via('relDecomposed');
	}

}