<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline\active_record;

use app\components\pozitronik\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_beeline_tribe".
 *
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property null|int $hr_group_id
 * @property int $domain
 */
class ImportBeelineTribe extends ActiveRecord {
	use ARExtended;
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_beeline_tribe';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user_id', 'domain'], 'integer'],
			[['domain'], 'required'],
			['hr_group_id', 'integer']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'user_id' => 'User ID',
			'domain' => 'Domain',
		];
	}
}
