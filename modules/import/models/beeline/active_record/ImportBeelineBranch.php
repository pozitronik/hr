<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline\active_record;

use yii\db\ActiveQuery;

/**
 * Class ImportBeelineBusinessBlock
 * @property-read ImportBeelineDecomposed[] $relDecomposed
 * @property-read ImportBeelineGroup[] $relGroup
 */
class ImportBeelineBranch extends ImportBeelineBase {

	/**
	 * @inheritDoc
	 */
	public static function tableName():string {
		return 'import_beeline_branch';
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelDecomposed():ActiveQuery {
		return $this->hasMany(ImportBeelineDecomposed::class, ['branch_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelGroup():ActiveQuery {
		return $this->hasMany(ImportBeelineGroup::class, ['id' => 'group_id'])->via('relDecomposed');
	}
}