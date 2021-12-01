<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline\active_record;

use yii\db\ActiveQuery;

/**
 * Class ImportBeelineBusinessBlock
 *
 * @property-read ImportBeelineDecomposed[] $relDecomposed
 * @property-read ImportBeelineFunctionalBlock[] $relFunctionalBlock
 */
class ImportBeelineBusinessBlock extends ImportBeelineBase {

	/**
	 * @inheritDoc
	 */
	public static function tableName():string {
		return 'import_beeline_business_block';
	}


	/**
	 * @return ActiveQuery
	 */
	public function getRelDecomposed():ActiveQuery {
		return $this->hasMany(ImportBeelineDecomposed::class, ['business_block_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelFunctionalBlock():ActiveQuery {
		return $this->hasMany(ImportBeelineFunctionalBlock::class, ['id' => 'functional_block_id'])->via('relDecomposed');
	}

}