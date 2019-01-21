<?php
declare(strict_types = 1);

namespace app\models\imports\fos;

use app\models\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_tribe".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $leader_id key to tribe leader id
 * @property int $leader_it_id key to tribe leader it id
 * @property int $domain
 * @property null|int $hr_group_id
 */
class ImportFosTribe extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_fos_tribe';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['leader_id', 'leader_it_id'], 'integer'],
			[['code', 'name'], 'string', 'max' => 255],
			['domain', 'integer'], ['domain', 'required'],
			['hr_group_id', 'integer']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'code' => 'Code',
			'name' => 'Name',
			'leader_id' => 'key to tribe leader id',
			'leader_it_id' => 'key to tribe leader it id'
		];
	}
}
