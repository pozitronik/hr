<?php
declare(strict_types = 1);

namespace app\modules\targets\models;

use app\models\core\ActiveRecordExtended;

/**
 * Class Targets
 */
class Targets extends ActiveRecordExtended {

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return '{{%target}}';
	}

}