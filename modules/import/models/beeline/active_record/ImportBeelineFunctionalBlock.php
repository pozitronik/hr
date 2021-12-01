<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline\active_record;

use yii\db\ActiveQuery;

/**
 * Class ImportBeelineBusinessBlock
 *
 * @property-read ImportBeelineDecomposed[] $relDecomposed
 * @property-read ImportBeelineDirection[] $relDirection
 */
class ImportBeelineFunctionalBlock extends ImportBeelineBase {

	/**
	 * @inheritDoc
	 */
	public static function tableName():string {
		return 'import_beeline_functional_block';
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelDecomposed():ActiveQuery {
		return $this->hasMany(ImportBeelineDecomposed::class, ['functional_block_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelDirection():ActiveQuery {
		return $this->hasMany(ImportBeelineDirection::class, ['id' => 'direction_id'])->via('relDecomposed');
	}


}