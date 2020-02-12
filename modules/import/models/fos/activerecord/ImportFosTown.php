<?php
declare(strict_types = 1);

namespace app\modules\import\models\fos\activerecord;

use pozitronik\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_fos_town".
 * Справочник городов
 *
 * @property int $id
 * @property string $name
 * @property int $domain
 */
class ImportFosTown extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_fos_town';
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
