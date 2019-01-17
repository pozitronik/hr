<?php
declare(strict_types = 1);

namespace app\models\imports\fos;

use app\models\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_command_position".
 *
 * @property int $id
 * @property string $code
 * @property string $name
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
			[['code', 'name'], 'string', 'max' => 255]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'code' => 'Code',
			'name' => 'Name'
		];
	}
}
