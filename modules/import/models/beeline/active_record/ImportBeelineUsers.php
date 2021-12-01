<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline\active_record;

use app\components\pozitronik\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_users".
 *
 * @property int $id
 * @property int|null $user_tn
 * @property string $name
 * @property int $position_id key to position id
 * @property int $level ceo level
 * @property int $domain
 * @property null|int $hr_user_id
 *
 */
class ImportBeelineUsers extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_fos_users';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			['user_tn', 'integer'],//kinda
			[['position_id', 'level'], 'integer'],
			[['name'], 'string', 'max' => 255],
			['domain', 'integer'], ['domain', 'required'],
			['hr_user_id', 'integer']
		];
	}

}
