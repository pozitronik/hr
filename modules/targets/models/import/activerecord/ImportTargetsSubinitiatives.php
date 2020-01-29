<?php

namespace app\modules\targets\models\import\activerecord;

use app\models\core\traits\ARExtended;
use Yii;

/**
 * This is the model class for table "import_targets_subinitiatives".
 *
 * @property int $id
 * @property string $initiative
 * @property int $domain
 * @property int|null $hr_target_id
 */
class ImportTargetsSubinitiatives extends \yii\db\ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'import_targets_subinitiatives';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['initiative', 'domain'], 'required'],
			[['domain', 'hr_target_id'], 'integer'],
			[['initiative'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'initiative' => 'Initiative',
			'domain' => 'Domain',
			'hr_target_id' => 'Hr Target ID',
		];
	}
}
