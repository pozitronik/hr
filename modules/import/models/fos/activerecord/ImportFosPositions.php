<?php
declare(strict_types = 1);

namespace app\modules\import\models\fos\activerecord;

use app\components\pozitronik\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_positions".
 * Справочник должностей при декомпозиции импорта из САП.
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
			['name', 'unique'],
			[['name'], 'string', 'max' => 255],
			['domain', 'integer'], ['domain', 'required']
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
