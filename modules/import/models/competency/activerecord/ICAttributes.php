<?php
declare(strict_types = 1);

namespace app\modules\import\models\competency\activerecord;

use app\models\core\traits\ARExtended;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "import_competency_attributes".
 *
 * @property int $id
 * @property string $name Название атрибута
 * @property int $hr_attribute_id id в системе
 * @property int $domain
 */
class ICAttributes extends ActiveRecord {
	use ARExtended;
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'import_competency_attributes';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name'], 'required'],
			[['hr_attribute_id'], 'integer'],
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
			'name' => 'Название атрибута',
			'hr_attribute_id' => 'id в системе'
		];
	}
}
