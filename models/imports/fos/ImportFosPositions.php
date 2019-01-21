<?php
declare(strict_types = 1);

namespace app\models\imports\fos;

use app\models\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_positions".
 *
 * @property int $id
 * @property string $name
 * @property int $domain
 */
class ImportFosPositions extends ActiveRecord {
	use ARExtended;
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_fos_positions';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'string', 'max' => 255],
			['domain', 'integer']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Name'
		];
	}
}
