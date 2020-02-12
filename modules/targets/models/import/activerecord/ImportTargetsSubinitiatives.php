<?php
declare(strict_types = 1);

namespace app\modules\targets\models\import\activerecord;

use pozitronik\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_targets_subinitiatives".
 *
 * @property int $id
 * @property string $initiative
 * @property int $domain
 * @property int|null $hr_target_id
 */
class ImportTargetsSubinitiatives extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_targets_subinitiatives';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['initiative', 'domain'], 'required'],
			[['domain', 'hr_target_id'], 'integer'],
			[['initiative'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'initiative' => 'Initiative',
			'domain' => 'Domain',
			'hr_target_id' => 'Hr Target ID',
		];
	}
}
