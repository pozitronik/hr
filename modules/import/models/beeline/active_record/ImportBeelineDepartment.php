<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline\active_record;

use yii\db\ActiveQuery;

/**
 * Class ImportBeelineBusinessBlock
 * @property-read ImportBeelineDecomposed[] $relDecomposed
 * @property-read ImportBeelineService[] $relService
 */
class ImportBeelineDepartment extends ImportBeelineBase {

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
		return $this->hasMany(ImportBeelineDecomposed::class, ['department_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelService():ActiveQuery {
		return $this->hasMany(ImportBeelineService::class, ['id' => 'service_id'])->via('relDecomposed');
	}

}