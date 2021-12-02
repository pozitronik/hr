<?php
declare(strict_types = 1);

namespace app\modules\import\models\beeline\active_record;

use app\components\pozitronik\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_beeline_boss".
 *
 * @property int $id
 * @property string $name
 * @property string $position
 * @property int $level
 * @property int $hr_user_id
 * @property int $domain
 */
class ImportBeelineBoss extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_beeline_boss';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['level', 'hr_user_id', 'domain'], 'integer'],
			[['name', 'position'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Name',
			'position' => 'Position',
			'level' => 'Level',
			'hr_user_id' => 'Hr User ID',
			'domain' => 'domain'
		];
	}
}
