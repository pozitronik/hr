<?php
declare(strict_types = 1);

namespace app\modules\import\models\fos;

use app\models\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_command_position".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $domain
 */
class ImportFosCommandPosition extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_fos_command_position';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			['position_id', 'integer'],
			[['code', 'name'], 'string', 'max' => 255],
			['domain', 'integer'], ['domain', 'required']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'position_id' => 'ID',
			'code' => 'Code',
			'name' => 'Name'
		];
	}
}
