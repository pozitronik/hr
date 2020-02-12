<?php
declare(strict_types = 1);

namespace app\modules\import\models\competency\activerecord;

use pozitronik\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_competency_fields".
 *
 * @property int $id
 * @property int $attribute_id Ключ к атрибуту
 * @property string $name
 * @property int $domain
 */
class ICFields extends ActiveRecord {
	use ARExtended;
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_competency_fields';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['attribute_id', 'name'], 'required'],
			[['attribute_id'], 'integer'],
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
			'attribute_id' => 'Ключ к атрибуту',
			'name' => 'Name'
		];
	}
}
