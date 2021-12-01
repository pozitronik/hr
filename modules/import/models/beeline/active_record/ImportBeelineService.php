<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline\active_record;

/**
 * Class ImportBeelineBusinessBlock
 */
class ImportBeelineService extends ImportBeelineBase {

	/**
	 * @inheritDoc
	 */
	public static function tableName():string {
		return 'import_beeline_service';
	}

}