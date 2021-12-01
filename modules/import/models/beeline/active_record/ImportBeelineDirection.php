<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline\active_record;

use yii\db\ActiveQuery;

/**
 * Class ImportBeelineBusinessBlock
 * @property-read ImportBeelineDecomposed[] $relDecomposed
 * @property-read ImportBeelineDepartment[] $relDepartment
 *
 */
class ImportBeelineDirection extends ImportBeelineBase {

	/**
	 * @inheritDoc
	 */
	public static function tableName():string {
		return 'import_beeline_department';
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelDecomposed():ActiveQuery {
		return $this->hasMany(ImportBeelineDecomposed::class, ['direction_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelDepartment():ActiveQuery {
		return $this->hasMany(ImportBeelineDepartment::class, ['id' => 'department_id'])->via('relDecomposed');
	}

}