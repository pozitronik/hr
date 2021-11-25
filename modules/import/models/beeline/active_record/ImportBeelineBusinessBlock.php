<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline\active_record;

use app\modules\import\models\beeline\activerecord\ImportBeelineBase;

/**
 * Class ImportBeelineBusinessBlock
 */
class ImportBeelineBusinessBlock extends ImportBeelineBase {

	/**
	 * @inheritDoc
	 */
	public static function tableName():string {
		return 'import_beeline_business_block';
	}

}